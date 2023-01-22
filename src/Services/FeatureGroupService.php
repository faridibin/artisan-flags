<?php

namespace Faridibin\Laraflags\Services;

use Faridibin\Laraflags\Repositories\FeatureGroupsRepository;

class FeatureGroupService
{
    /**
     * The feature repository instance.
     *
     * @var \Faridibin\Laraflags\Repositories\FeatureGroupsRepository
     */
    protected $repository;

    /**
     * Create a new repository instance.
     */
    public function __construct()
    {
        $this->repository = new FeatureGroupsRepository();
    }

    /**
     * Gets all feature groups.
     *
     * @param array<string> $columns
     * @return \Illuminate\Database\Eloquent\Collection<\Faridibin\Laraflags\Models\FeatureGroups>
     */
    public function getAll(array $columns = ['*'])
    {
        return $this->repository->getAll($columns);
    }

    /**
     * Gets a feature by id.
     *
     * @param int $id
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    /**
     * Creates a new feature.
     *
     * @param array<string, mixed> $data
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Updates a feature.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Deletes a feature.
     *
     * @param int $id
     * @return \Faridibin\Laraflags\Models\FeatureGroups
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
