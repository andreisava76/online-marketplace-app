<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /** @var Comment  */
    public Comment $comment;
    /** @var string  */
    public string $eventName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->eventName = 'new.comment';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('listing.' . $this->comment->listing_id);
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        $html = view('components._partials.comment')->with(["comment" => $this->comment])->render();
        return [
            "html" => $html
        ];
    }
    public function broadcastAs(): string
    {
        return $this->eventName;
    }
}
