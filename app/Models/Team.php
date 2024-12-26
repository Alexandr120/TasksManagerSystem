<?php

namespace App\Models;

use App\Repositories\Contract\WithRepository;
use App\Repositories\TeamRepository;
use App\Services\Contract\WithService;
use App\Services\TeamService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    use WithRepository, WithService;

    protected $fillable = [
        'name'
    ];

    protected string $repository = TeamRepository::class;

    protected string $service = TeamService::class;

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_teams');
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }
}
