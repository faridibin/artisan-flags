<?php

namespace Faridibin\Laraflags\Models;

use Faridibin\Laraflags\Database\Factories\FeatureGroupFactory;
use Faridibin\Laraflags\Models\Concerns\HasFlagFeatures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeatureGroups extends Model
{
    use HasFactory, HasFlagFeatures;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'active',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * A new factory instance for the model.
     *
     * @return \Faridibin\Laraflags\Database\Factories\FeatureGroupFactory
     */
    public static function newFactory(): FeatureGroupFactory
    {
        return FeatureGroupFactory::new();
    }

    /**
     * The features that belongs to the feature group.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Features::class, 'feature_feature_group', 'feature_group_id', 'feature_id')->withTimestamps();
    }

    /**
     * Determine if the feature group has a given feature.
     *
     * @param string $feature
     * @return bool
     */
    public function hasFeature($feature): bool
    {
        return $this->features->contains('name', $feature);
    }
}
