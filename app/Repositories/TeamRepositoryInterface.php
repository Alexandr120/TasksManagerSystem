<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    /**
     * @return Collection|null
     */
    public function getTeamsListForSelect(): ?Collection;

    /**
     * @return Collection|null
     */
    public function usersList(): ?Collection;

    /**
     * @param Collection $filters
     * @return Builder
     */
    public function getTeamsListWithFilters(\Illuminate\Support\Collection $filters): Builder;
}
