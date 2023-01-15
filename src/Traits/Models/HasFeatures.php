<?php

namespace Faridibin\Laraflags\Traits\Models;

use Faridibin\Laraflags\Models\Features;
use Faridibin\Laraflags\Repositories\FeaturesRepository;

trait HasFeatures
{
    /**
     * Get a feature.
     *
     * @param string|int $indetifier
     * @return \Faridibin\Laraflags\Models\Features|bool
     */
    public function feature(string|int $indetifier): Features|bool
    {
        $repository = new FeaturesRepository();
        $feature = gettype($indetifier) === 'string' ? $repository->getByName($indetifier) : $repository->getById($indetifier);

        return $feature ?? false;
    }
}
