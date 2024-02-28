<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class ChatMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['diff_for_humans', 'name_sender'];

    public function getDiffForHumansAttribute()
    {

        return $this->created_at->diffForHumans();

    }

    public function getNameSenderAttribute()
    {

        return User::query()->where('id', $this->sender_id)->value('name');

    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id', 'id');
    }

}
