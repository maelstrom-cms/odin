<?php

namespace App\Notifications;

use App\Website;
use App\CrawledPage;
use App\CertificateScan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BrowserMessageDetected extends Notification
{
    use Queueable;

    /**
     * @var Website
     */
    private $website;

    /**
     * @var CrawledPage
     */
    private $page;

    /**
     * Create a new notification instance.
     *
     * @param Website $website
     * @param CrawledPage $page
     */
    public function __construct(Website $website, CrawledPage $page)
    {
        $this->website = $website;
        $this->page = $page;
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
            ->subject('📟 Browser Message Detected: ' . $this->website->url)
            ->markdown('mail.browser-message', [
                'website' => $this->website,
                'page' => $this->page,
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
            'website' => $this->website->url,
            'url' => $this->page->url,
            'messages' => $this->page->messages,
        ];
    }
}
