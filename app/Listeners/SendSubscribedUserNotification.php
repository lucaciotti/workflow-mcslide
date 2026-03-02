<?php

namespace App\Listeners;

use App\Models\Task;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Kirschbaum\Commentions\Events\UserIsSubscribedToCommentableEvent;

class SendSubscribedUserNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserIsSubscribedToCommentableEvent $event): void
    {
        $author = User::find($event->comment->author_id);
        $recip = User::find($event->user->id);
        $task = Task::find($event->comment->commentable_id);
        Notification::make()
            ->title($author->name . ' ha commentato l\'Ordine n. ' . $task->num)
            ->body($event->comment->body)
            ->sendToDatabase($recip);
    }
}
