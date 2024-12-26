<?php

namespace App\Repositories\Contract;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Find model by id
     *
     * @return Collection|null
     */
    public function all(): ?Collection;

    /**
     * Find model by id
     *
     * @param int|string $id
     * @return Model|null
     */
    public function findById(int|string $id): ?Model;
}
