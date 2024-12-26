<?php

namespace App\Services\Contract;

use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface
{
    /**
     * Create model with data
     *
     * @param array $data
     * @return Model|null
     */
    public function create(array $data): ?Model;

    /**
     * Update model
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool;

    /**
     * Delete model
     *
     * @return bool
     */
    public function delete(): bool;
}
