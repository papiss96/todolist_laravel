<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TodoAffected extends Notification
{
    use Queueable;
    public $todo;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($todo)
    {
        $this->todo = $todo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
                    ->from('Meuz@Laravel_todo','Laravel_todo')
                    ->subject('Tu a une nouvelle todo à finir')
                    ->line("la todo (#".$this->todo->id.")'".$this->todo->name."' vient de t etre affecter par ".$this->todo->todoAffectedBy->name.".")
                    ->action('Voir toute les todos', url('/todos'))
                    ->line('Merci pour utilisation de notre  application!');
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
            'todo_id'=> $this->todo->id,
            'affected_by' => $this->todo->todoAffectedBy->name,
            'todo_name' => $this->todo->name,
        ];
    }
}
