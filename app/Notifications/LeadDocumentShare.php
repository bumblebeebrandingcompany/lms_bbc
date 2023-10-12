<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Utils\Util;
class LeadDocumentShare extends Notification
{
    use Queueable;

    public $lead;
    public $document;
    public $sharer;
    public $note;

    /**
     * Create a new notification instance.
     */
    public function __construct($lead, $document, $sharer, $note)
    {
        $this->lead = $lead;
        $this->document = $document;
        $this->sharer = $sharer;
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $util = new Util();
        $docGuestViewUrl = $util->generateGuestDocumentViewUrl($this->document->id);
        
        return (new MailMessage)
            ->greeting('Hello '. $this->lead->name)
            ->subject($this->sharer->name.' has shared a new document.')
            ->line('A new document '.$this->document->title.' is shared with you. View the document by clicking the button below.')
            ->action('View Document', $docGuestViewUrl)
            ->lineIf(!empty($this->note), "<u>Note</u>: ".nl2br($this->note));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
