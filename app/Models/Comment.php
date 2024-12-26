<?php

namespace App\Models;

use App\Services\CommentService;
use App\Services\Contract\WithService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use WithService;

    protected $fillable = [
        'task_id',
        'user_id',
        'content'
    ];

    protected string $service = CommentService::class;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
