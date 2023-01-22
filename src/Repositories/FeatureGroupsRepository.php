<?php

namespace Faridibin\Laraflags\Repositories;

use Faridibin\Laraflags\Interfaces\Repositories\LaraflagsRepositoryInterface;
use Faridibin\Laraflags\Models\FeatureGroups;

class FeatureGroupsRepository implements LaraflagsRepositoryInterface
{
    /**
     * The feature group model instance.
     *
     * @var \Faridibin\Laraflags\Models\FeatureGroups
     */
    protected $model;

    /**
     * Create a new repository instance.
     */
    public function __construct()
    {
        $this->model = new FeatureGroups();
    }

    /**
     * Gets all feature groups.
     *
     * param array<string> $columns
     * @return \Illuminate\Database\Eloquent\Collection<\Faridibin\Laraflags\Models\FeatureGroups>
     */
    public function getAll(array $columns): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Gets a feature group by id.
     *
     * @param int $id
     * @return FeatureGroups|null
     */
    public function getById(int $id): ?FeatureGroups
    {
        return $this->model->find($id);
    }

    /**
     * Gets a feature group by name.
     *
     * @param string $name
     * @return FeatureGroups|null
     */
    public function getByName(string $name): ?FeatureGroups
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Creates a new feature group.
     *
     * @param array<string, mixed> $data
     * @return FeatureGroups
     */
    public function create(array $data): FeatureGroups
    {
        return $this->model->create($data);
    }

    /**
     * Updates a feature.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return FeatureGroups
     */
    public function update(int $id, array $data): FeatureGroups
    {
        $featureGroup = $this->model->find($id);
        $featureGroup->update($data);

        return $featureGroup;
    }

    /**
     * Deletes a feature.
     *
     * @param int $id
     * @return FeatureGroups
     */
    public function delete(int $id): FeatureGroups
    {
        $featureGroup = $this->model->find($id);
        $featureGroup->delete();

        return $featureGroup;
    }
}
