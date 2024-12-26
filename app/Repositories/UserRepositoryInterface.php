<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Get only Users ids
     *
     * @return Collection
     */
    public function getUsersIds(): Collection;
}
