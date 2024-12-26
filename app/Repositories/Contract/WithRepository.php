<?php

namespace App\Repositories\Contract;

use App\Exceptions\DependenciesException;

/**
 * @template T
 */
trait WithRepository
{
    /**
     * @return T
     */
    public function repository()
    {
        $repository = match (true) {
            /** @psalm-suppress UndefinedThisPropertyFetch */
            property_exists($this, 'repository') => $this->repository,
            method_exists($this, 'repository') => $this->repository(),
            default => null,
        };

        if (! is_a($repository, BaseRepository::class, true)) {
            throw DependenciesException::create($repository);
        }

        return new $repository($this);
    }
}
