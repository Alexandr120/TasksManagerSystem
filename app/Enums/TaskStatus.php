<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum TaskStatus: int
{
    use InvokableCases, Options, Values;

    case PENDING = 1;
    case IN_PROGRESS = 2;
    case COMPLETED = 3;

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isInProgress(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function desc()
    {
        $statuses = self::forSelect();

        return $statuses[$this->value];
    }

    public static function forSelect(): array
    {
        return [
            self::PENDING() => __('Pending'),
            self::IN_PROGRESS() => __('In progress'),
            self::COMPLETED() => __('Completed')
        ];
    }
}
