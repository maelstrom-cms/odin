<?php

namespace App\Checkers;

use App\Website;
use App\CertificateScan;
use VisualAppeal\SslLabs;
use App\Notifications\CertificateIsWeak;
use Spatie\SslCertificate\SslCertificate;
use App\Notifications\CertificateIsInvalid;
use App\Notifications\CertificateWillExpire;
use App\Notifications\CertificateHasExpired;
use App\Notifications\CertificateIsExpiring;

class Certificate
{
    private $website;

    private $scan;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function run()
    {
        $this->fetch();
        $this->notify();
    }

    private function fetch()
    {
        $certificate = SslCertificate::createForHostName($this->website->certificate_hostname);

        $scan = new CertificateScan([
            'issuer' => $certificate->getIssuer(),
            'domain' => $certificate->getDomain(),
            'additional_domains' => $certificate->getAdditionalDomains(),
            'valid_from' => $certificate->validFromDate(),
            'valid_to' => $certificate->expirationDate(),
            'was_valid' => $certificate->isValid(),
            'did_expire' => $certificate->isExpired(),
            'grade' => 'N/A',
        ]);

        $this->website->certificates()->save($scan);

        $labs = new SslLabs();

        $result = $labs->analyze(
            $this->website->certificate_hostname,
            $publish = false,
            $startNew = false,
            $fromCache = true,
            $maxAge = 12,
            $all = null,
            $ignoreMismatch = false
        );

        foreach ($result->endpoints ?? [] as $endpoint) {
            if ($endpoint->statusMessage === 'Ready') {
                $scan->grade = $endpoint->grade;
                $scan->save();
                $this->scan = $scan;
                break;
            }
        }

        if (app()->runningInConsole()) {
            if (!$scan->exists) {
                dump('Scan still in progress...');
                dump($result);
            }
        }
    }

    private function notify($notification = null)
    {
        $this->scan = $this->website->certificates()->latest()->first();

        if (!$this->scan) {
            return null;
        }

        if (!$this->scan->was_valid) {
            $notification = new CertificateIsInvalid($this->website, $this->scan);
        } elseif ($this->scan->did_expire) {
            $notification = new CertificateHasExpired($this->website, $this->scan);
        } elseif (now()->diffInHours($this->scan->valid_to) <= 24) {
            $notification = new CertificateIsExpiring($this->website, $this->scan);
        } elseif (now()->diffInDays($this->scan->valid_to) <= 7) {
            $notification = new CertificateWillExpire($this->website, $this->scan);
        } elseif (in_array($this->scan->grade, ['C', 'D', 'E', 'F'])) {
            $notification = new CertificateIsWeak($this->website, $this->scan);
        }

        if (!$notification) {
            return null;
        }

        $this->website->user->notify($notification);
    }
}
