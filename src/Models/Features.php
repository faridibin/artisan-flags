<?php

namespace Faridibin\Laraflags\Models;

use Faridibin\Laraflags\Database\Factories\FeatureFactory;
use Faridibin\Laraflags\Models\Concerns\HasFlagFeatures;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Features extends Model
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
     * @return \Faridibin\Laraflags\Database\Factories\FeatureFactory
     */
    public static function newFactory(): FeatureFactory
    {
        return FeatureFactory::new();
    }

    /**
     * The feature groups that feature belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(FeatureGroups::class, 'feature_feature_group', 'feature_id', 'feature_group_id')->withTimestamps();
    }

    /**
     * Determine if the feature belongs to a given group.
     *
     * @param string $group
     * @return bool
     */
    public function inGroup($group): bool
    {
        return $this->groups->contains('name', $group);
    }
}
