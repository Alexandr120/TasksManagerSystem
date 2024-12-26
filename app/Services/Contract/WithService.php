<?php

namespace App\Services\Contract;

use App\Exceptions\DependenciesException;

/**
 * @template T
 */
trait WithService
{
    /**
     * @return T
     */
    public function service()
    {
        $service = match (true) {
            /** @psalm-suppress UndefinedThisPropertyFetch */
            property_exists($this, 'service') => $this->service,
            method_exists($this, 'service') => $this->service(),
            default => null,
        };

        if (! is_a($service, BaseService::class, true)) {
            throw DependenciesException::create($service);
        }

        return new $service($this);
    }
}
