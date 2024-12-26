<?php

namespace App\Data\TablesFilters;

trait PageFiltersData
{
    public string|int $page = 1;

    public string|int $per_page = 20;

    public string $direction = 'desc';
}
