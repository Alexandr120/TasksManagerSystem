<?php

namespace App\Repositories\Contract;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): ?Collection
    {
        return $this->model::all();
    }

    public function queryBuilder(): Builder
    {
        return $this->model->newQuery();
    }

    public function findById(int|string $id): ?Model
    {
        return $this->model::find($id);
    }
}
