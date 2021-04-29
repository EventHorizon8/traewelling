<?php

namespace App\Notifications;

use App\Exceptions\ShouldDeleteNotificationException;
use App\Models\FollowRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowRequestIssued extends Notification
{
    use Queueable;

    public FollowRequest $followRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FollowRequest $followRequest = null) {
        $this->followRequest = $followRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            'follow_id' => $this->followRequest->id,
        ];
    }

    /**Detail-Handler of notification
     *
     * @param mixed $notification
     * @throws ShouldDeleteNotificationException
     */
    public static function detail($notification) {
        $data = $notification->data;
        $notification->detail = new \stdClass();
        try {
            $followRequest = FollowRequest::findOrFail($data['follow_id']);
            $sender = User::findOrFail($followRequest->user_id);
        } catch (ModelNotFoundException $e) {
            // The follow request doesn't exist anymore or the user doesn't exist anymore
            throw new ShouldDeleteNotificationException();
        }
        $notification->detail->followRequest = $followRequest;
        $notification->detail->sender = $sender;

        return $notification->detail;
    }

    public static function render($notification) {
        try {
            $detail = self::detail($notification);
        } catch (ShouldDeleteNotificationException) {
            $notification->delete();
            return null;
        }

        return view("includes.notification", [
            'color' => 'neutral',
            'icon' => 'fas fa-user-plus',
            'lead' => __('notifications.userRequestedFollow.lead', ['followerRequestUsername' => $detail->sender->username]),
            'link' => route('settings.follower'),
            'notice' => __('notifications.userRequestedFollow.notice'),
            'date_for_humans' => $notification->created_at->diffForHumans(),
            'read'            => $notification->read_at != null,
            'notificationId'  => $notification->id
        ])->render();
    }
}
