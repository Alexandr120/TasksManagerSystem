<?php

namespace App\Services\Contract;

use Illuminate\Database\Eloquent\Model;

class BaseService implements BaseServiceInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function update(array $data): bool
    {
        return $this->model->update($data);
    }

    public function delete(): bool
    {
        return $this->model->delete();
    }
}
