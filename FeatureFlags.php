<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait FeatureFlags
{
    /**
     * Boot function from laravel.
     */
    protected static function bootFeatureFlags()
    {
        static::creating(function ($model) {
            $model->name = (string) Str::slug($model->name, '-');
        });
    }

    /**
     * Scope a query to only include active features.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Activate the feature.
     *
     * @return void
     */
    public function activate()
    {
        $this->active = true;

        $this->save();
    }

    /**
     * Deactivate the feature.
     *
     * @return void
     */
    public function deactivate()
    {
        $this->active = false;

        $this->save();
    }
}
