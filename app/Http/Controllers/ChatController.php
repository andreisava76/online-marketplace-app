<?php

namespace App\Http\Controllers;

use App\Events\NewMessageReceived;
use App\Events\SendMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function auth;

class ChatController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('components.chat.chat', [
            'chat_rooms' => ChatRoom::query()
                ->where('sender_id', auth()->id())
                ->orWhere('recipient_id', auth()->id())
                ->with('latestMessage')
                ->get()
                ->sortByDesc('latestMessage.created_at')
        ]);
    }


    /**
     * @throws ValidationException
     */
    public function findOrNew(Request $request): Application|Factory|View
    {
        $this->validate($request, [
                'sender_id' => ['required', Rule::exists('users', 'id')],
                'recipient_id' => ['required', Rule::exists('users', 'id')]
            ]
        );

        $chat_rooms = ChatRoom::query()->where('sender_id', auth()->id())
            ->orWhere('recipient_id', auth()->id())
            ->latest()->get();

        $response_chat_room = ChatRoom::query()
            ->where([
                ['sender_id', $request->get('sender_id')],
                ['recipient_id', $request->get('recipient_id')],
            ])->orWhere([
                ['sender_id', $request->get('recipient_id')],
                ['recipient_id', $request->get('sender_id')],
            ])->first();

        if (!$response_chat_room) {
            $response_chat_room = ChatRoom::query()->firstOrNew([
                'sender_id' => intval($request->get('sender_id')),
                'recipient_id' => intval($request->get('recipient_id')),
            ]);

            $chat_rooms->prepend($response_chat_room);
        }

        return view('components.chat.chat', compact('chat_rooms', 'response_chat_room'));
    }

    public function show($room_id): Collection|array
    {
        return ChatMessage::with('room.user')->where('chat_room_id', $room_id)
            ->latest()->get();
    }

    /**
     * @throws ValidationException
     */

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'message' => ['required', 'min:3'],
            'recipient_id' => ['required', Rule::exists('users', 'id')]
        ]);

        $chat_room_id = $request->get('chat_room_id');

        if (!$chat_room_id) {
            $chat_room = ChatRoom::query()->create([
                'sender_id' => auth()->id(),
                'recipient_id' => $request->get('recipient_id')
            ]);
            $chat_room_id = $chat_room->id;
        }

        $new_message = new ChatMessage;
        $new_message->chat_room_id = $chat_room_id;
        $new_message->sender_id = auth()->id();
        $new_message->recipient_id = $request->get('recipient_id');
        $new_message->message = $request->get('message');
        $new_message->save();

        event(new SendMessage($new_message));
        event(new NewMessageReceived($new_message));
        return response()->json([
            'html_sender' => view('components._partials.message_sender')->with(["message" => $new_message])->render(),
            'html_recipient' => view('components._partials.message_recipient')->with(["message" => $new_message])->render(),
            'message' => $new_message,
        ]);
    }
}

