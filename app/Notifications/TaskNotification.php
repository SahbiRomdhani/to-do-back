<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskNotification extends Notification
{
    use Queueable;

    public $task;
    /**
     * Create a new message instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //return ['mail', 'vonage'];
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Task Reminder')
                ->line('Your task "'.$this->task->title.'" is due.')
                ->line('Due Date: '.$this->task->due_date);
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
                ->clientReference((string) $notifiable->id)
                ->content('Your task "'.$this->task->title.'" is due.');
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
