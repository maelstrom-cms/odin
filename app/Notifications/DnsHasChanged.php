<?php

namespace App\Notifications;

use App\Website;
use App\DnsScan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DnsHasChanged extends Notification
{
    use Queueable;

    /**
     * @var Website
     */
    private $website;

    /**
     * @var DnsScan
     */
    private $scan;

    /**
     * Create a new notification instance.
     *
     * @param Website $website
     * @param DnsScan $scan
     */
    public function __construct(Website $website, DnsScan $scan)
    {
        $this->website = $website;
        $this->scan = $scan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📚 Change detected on: ' . $this->website->url)
            ->markdown('mail.dns-changed', [
                'website' => $this->website,
                'scan' => $this->scan,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'website_id' => $this->website->id,
            'url' => $this->website->dns_hostname,
            'changed_on' => $this->scan->created_at,
            'flat' => $this->scan->flat,
            'diff' => $this->scan->diff,
        ];
    }
}
