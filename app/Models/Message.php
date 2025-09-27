<?php

namespace App\Models;

use App\Enums\EnumsUserMessages;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'content', 'sender'];
    protected $casts = [
        'sender' => EnumsUserMessages::class
    ];
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
