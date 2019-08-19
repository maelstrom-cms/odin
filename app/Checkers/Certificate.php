<?php

namespace App\Checkers;

use App\Website;
use Andyftw\SSLLabs\Api;
use App\CertificateScan;
use Spatie\SslCertificate\SslCertificate;

class Certificate
{
    private $website;

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
        ]);

        $labs = new \VisualAppeal\SslLabs();

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
                $this->website->certificates()->save($scan);
                break;
            }
        }

        dump($scan->exists ? 'Cert Updated' : 'Pending...');
    }

    private function notify()
    {

    }
}
