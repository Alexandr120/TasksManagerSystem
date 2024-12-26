<?php

namespace App\Data\TablesFilters;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class TaskFilterData extends Data
{
    use PageFiltersData;

    public ?string $status = '';

    public ?string $title = '';

    public ?string $team = '';

    public ?string $user = '';

    public ?string $created_at = '';

    public ?string $updated_at = '';

    public function toCollect(): Collection
    {
        return new Collection($this->all());
    }
}
