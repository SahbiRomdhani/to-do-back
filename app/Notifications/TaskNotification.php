<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;


class TaskNotification extends Notification implements ShouldQueue
{

    use Queueable;

    public $task;
    /**
     * Create a new message instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
        $this->onConnection('redis');

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //return ['mail', 'vonage'];
        return ['mail',];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/tasks/show/'.$this->task->id); // put front-end url

    return (new MailMessage)
                ->greeting('Hello!')
                ->subject('Task Reminder')
                ->line('Your task "'.$this->task->title.'" is due.')
                ->line('Due Date: '.$this->task->created_at)
                ->action('View Task', $url)
                ->line('Thank you for using our application!');
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
