<?php

namespace App\Notifications;

use App\Website;
use App\VisualDiff;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisualDifferenceFound extends Notification
{
    use Queueable;

    /**
     * @var Website
     */
    private $website;

    /**
     * @var VisualDiff
     */
    private $scan;

    /**
     * Create a new notification instance.
     *
     * @param Website $website
     * @param VisualDiff $scan
     */
    public function __construct(Website $website, VisualDiff $scan)
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🌄 Visual Difference on: ' . $this->website->url)
            ->markdown('mail.visual-diff', [
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
            'website' => $this->website,
            'scan' => $this->scan,
        ];
    }
}
