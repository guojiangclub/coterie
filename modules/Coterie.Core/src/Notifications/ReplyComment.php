<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 回复评论通知
 * Class ReplyComment.
 */
class ReplyComment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coterie, $reply_user, $reply, $content, $comment)
    {
        $this->type = 'ReplyComment';
        $this->reply_user = $reply_user;
        $this->coterie = $coterie;
        $this->reply = $reply;
        $this->content = $content;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'coterie' => $this->coterie,
            'reply_user' => $this->reply_user,
            'reply' => $this->reply,
            'comment' => $this->comment,
            'content' => $this->content,
        ];
    }
}
