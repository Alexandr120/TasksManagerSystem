<?php

namespace App\Repositories;

use App\Repositories\Contract\BaseRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    public function getTeamsListForSelect(): ?Collection
    {
        return $this->model->all()->pluck('name', 'id');
    }

    public function usersList(): ?Collection
    {
        return $this->model->users->pluck('name', 'id');
    }

    public function getTeamsListWithFilters(\Illuminate\Support\Collection $filters): Builder
    {
        $query = $this->queryBuilder();

        $filters->filter(function ($filter) { return $filter != ''; })
            ->map(function ($value, $filter) use (&$query){
                match ($filter) {
                    'name' => $query->where($filter, 'like', '%' . $value . '%'),
                    'created_at' => $query->whereRaw("DATE(created_at) = '" . $value . "'"),
                    'updated_at' => $query->whereRaw("DATE(updated_at) = '" . $value . "'"),
                    default => $query
                };
            });

        return $query;
    }
}
