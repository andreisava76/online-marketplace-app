<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;
    public string $eventName;

    /**
     *
     * @return void
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->eventName = 'new.message';
    }

    public function broadcastWith(): array
    {
        $html_sender = view('components._partials.message_sender')->with(["message" => $this->message])->render();
        $html_recipient = view('components._partials.message_recipient')->with(["message" => $this->message])->render();

        return [
            "html_sender" => $html_sender,
            "html_recipient" => $html_recipient,
            "content" => $this->message
        ];
    }

    /**
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chatroom.' . $this->message->chat_room_id);
    }

    public function broadcastAs(): string
    {
        return $this->eventName;
    }
}
