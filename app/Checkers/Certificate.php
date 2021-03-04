<?php

namespace App\Checkers;

use App\Website;
use App\CertificateScan;
use App\IntermediateCertificateScan;
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

        # Do proper SSL Check of whole chain
        $ssloptions = array(
            "capture_peer_cert_chain" => true,
            "allow_self_signed"=>false,
            "CN_match"=>$this->website->certificate_hostname,
            "verify_peer"=>true,
            "SNI_enabled"=>true,
            "SNI_server_name"=>$this->website->certificate_hostname,
            "cafile"=>'/etc/ssl/certs/ca-certificates.crt' //mozilla ca cert bundle: http://curl.haxx.se/docs/caextract.html
        );

        $ctx = stream_context_create( array("ssl" => $ssloptions) );
        $result = stream_socket_client("ssl://".$this->website->certificate_hostname.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $ctx);
        $cont = stream_context_get_params($result);
        $first = True;
        $scan = Null;
        foreach($cont["options"]["ssl"]["peer_certificate_chain"] as $cert)
        {
            openssl_x509_export($cert, $pem_encoded);
            $certificate = SslCertificate::createFromString($pem_encoded);

            if ($first) {
                $first = False;

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
                continue;
            }

            $int_scan = new IntermediateCertificateScan([
                'issuer' => $certificate->getIssuer(),
                'certificate_scan_id' => $scan->id,
                'common_name' => $certificate->getDomain(),
                'valid_from' => $certificate->validFromDate(),
                'valid_to' => $certificate->expirationDate(),
                'was_valid' => $certificate->isValid(),
                'did_expire' => $certificate->isExpired(),
            ]);

            $int_scan->save();
        }

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

        # Check expiry of Intermediate Certificates
        foreach(IntermediateCertificateScan::where('certificate_scan_id', $this->scan->id)->get() as $int_cert) {
            if (!$int_cert->was_valid) {
                $notification = new CertificateIsInvalid($this->website, $this->scan);
            } elseif ($int_cert->did_expire) {
                $notification = new CertificateHasExpired($this->website, $this->scan);
            } elseif (now()->diffInHours($int_cert->valid_to) <= 24) {
                $notification = new CertificateIsExpiring($this->website, $this->scan);
            } elseif (now()->diffInDays($int_cert->valid_to) <= 7) {
                $notification = new CertificateWillExpire($this->website, $this->scan);
            }
        }

        if (!$notification) {
            return null;
        }

        $this->website->user->notify($notification);
    }
}
