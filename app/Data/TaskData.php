<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TaskData extends Data
{
    public function __construct(
        public string $title,
        public string $description,
        public string|int $status,
        public string|int $team_id,
        public string|int $user_id
    ) {}
}
