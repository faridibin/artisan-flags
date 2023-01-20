<?php

namespace Faridibin\Laraflags\Traits\Models;

use Faridibin\Laraflags\Repositories\TenantsRepository;
use Illuminate\Database\Eloquent\Model;

trait HasTenants
{
    /**
     * Get a feature.
     *
     * @param string|int $indetifier
     * @return \Illuminate\Database\Eloquent\Model|bool
     */
    public function tenant(string|int $indetifier): Model|bool
    {
        $repository = new TenantsRepository();
        $feature = gettype($indetifier) === 'string' ? $repository->getByName($indetifier) : $repository->getById($indetifier);

        return $feature ?? false;
    }
}
