<?php

namespace App\Models;

use App\Data\TaskData;
use App\Enums\TaskStatus;
use App\Repositories\Contract\WithRepository;
use App\Repositories\TaskRepository;
use App\Services\Contract\WithService;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use WithRepository, WithService;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'team_id',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    protected string $repository = TaskRepository::class;

    protected string $service = TaskService::class;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
