<?php

namespace App\Exceptions;

use Exception;

class DependenciesException extends Exception
{
    public static function create(?string $class)
    {
        $message = $class === null
            ? "Could not create a dependency class, no [ $class ] class was given"
            : "Could not create a [ $class ] class, `{$class}` does not implement `Base dependency`";

        return new self($message);
    }
}
