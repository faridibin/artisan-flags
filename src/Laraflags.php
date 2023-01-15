<?php

namespace Faridibin\Laraflags;

use Faridibin\Laraflags\Traits\HasConfigurations;
use Faridibin\Laraflags\Traits\Models\HasFeatureGroups;
use Faridibin\Laraflags\Traits\Models\HasFeatures;

class Laraflags
{
    use HasConfigurations, HasFeatureGroups, HasFeatures;

    /**
     * Create a new Laraflags instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
}
