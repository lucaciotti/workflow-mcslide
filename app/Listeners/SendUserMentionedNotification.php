<?php

namespace App\Listeners;

use App\Models\Task;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Kirschbaum\Commentions\Events\UserWasMentionedEvent;

class SendUserMentionedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserWasMentionedEvent $event): void
    {
        $author = User::find($event->comment->author_id);
        $recip = User::find($event->user->id);
        $task = Task::find($event->comment->commentable_id);
        Notification::make()
            ->title($author->name . ' ti ha citato nel Commento dell\'Ordine n. '. $task->num)
            ->body($event->comment->body)
            ->sendToDatabase($recip);
    }
}
