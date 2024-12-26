<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * Get collection by filters
     *
     * @param Collection $filters
     * @param User $user
     * @return Builder
     */
    public function getUserTaskListWithFilters(Collection $filters, ?User $user): Builder;
}
