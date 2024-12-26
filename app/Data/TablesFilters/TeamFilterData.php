<?php

namespace App\Data\TablesFilters;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class TeamFilterData extends Data
{
    use PageFiltersData;

    public ?string $name = '';

    public ?string $created_at = '';

    public ?string $updated_at = '';

    public function toCollect(): Collection
    {
        return new Collection($this->all());
    }
}
