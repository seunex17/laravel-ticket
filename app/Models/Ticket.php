<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'agent_id',
        'comment',
        'ticket_id',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
