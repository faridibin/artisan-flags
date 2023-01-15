<?php

namespace Faridibin\Laraflags\Traits\Models;

use Faridibin\Laraflags\Models\FeatureGroups;
use Faridibin\Laraflags\Repositories\FeatureGroupsRepository;

trait HasFeatureGroups
{
    /**
     * Get a feature group.
     *
     * @param string|int $indetifier
     * @return \Faridibin\Laraflags\Models\FeatureGroups|bool
     */
    public function group(string|int $indetifier): FeatureGroups|bool
    {
        $repository = new FeatureGroupsRepository();
        $group = gettype($indetifier) === 'string' ? $repository->getByName($indetifier) : $repository->getById($indetifier);

        return $group ?? false;
    }
}
