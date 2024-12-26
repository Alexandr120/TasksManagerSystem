<?php

namespace App\Repositories;

use App\Repositories\Contract\BaseRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Illuminate\Foundation\Auth\User;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function getUserTaskListWithFilters(Collection $filters, ?User $user): Builder
    {
        $query = $user ? $user->tasks() : $this->queryBuilder();

        $filters->filter(function ($filter) { return $filter != ''; })
            ->map(function ($value, $filter) use (&$query){
                match ($filter) {
                    'status' => $query->where($filter, $value),
                    'title' => $query->where($filter, 'like', '%' . $value . '%'),
                    'team' => ($value != 0)? $query->where('team_id', $value) : $query->whereNull('team_id'),
                    'user' => ($value != 0)? $query->whereHas('user', function ($q) use($value) {
                        $q->where('name', 'like', '%' . $value . '%');
                    }) : $query->whereNull('user_id'),
                    'created_at' => $query->whereRaw("DATE(created_at) = '" . $value . "'"),
                    'updated_at' => $query->whereRaw("DATE(updated_at) = '" . $value . "'"),
                    default => $query
                };
            });

        return $query;
    }
}
