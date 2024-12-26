<?php

namespace App\Repositories;

use App\Repositories\Contract\BaseRepository;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getUsersIds(): Collection
    {
        return $this->model->select('id')->pluck('id');
    }

    public function getUsersList(): Collection
    {
        return $this->model->select('id', 'name')->pluck('name', 'id');
    }
}
