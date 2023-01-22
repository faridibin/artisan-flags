<?php

namespace Faridibin\Laraflags\Repositories;

use Faridibin\Laraflags\Interfaces\Repositories\LaraflagsRepositoryInterface;
use Faridibin\Laraflags\Models\Features;

class FeaturesRepository implements LaraflagsRepositoryInterface
{
    /**
     * The feature model instance.
     *
     * @var \Faridibin\Laraflags\Models\Features
     */
    protected $model;

    /**
     * Create a new repository instance.
     */
    public function __construct()
    {
        $this->model = new Features();
    }

    /**
     * Gets all features.
     *
     * @param array<string> $columns
     * @return \Illuminate\Database\Eloquent\Collection<\Faridibin\Laraflags\Models\Features>
     */
    public function getAll(array $columns): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Gets a feature by id.
     *
     * @param int $id
     * @return Features|null
     */
    public function getById(int $id): ?Features
    {
        return $this->model->find($id);
    }

    /**
     * Gets a feature by name.
     *
     * @param string $name
     * @return Features|null
     */
    public function getByName(string $name): ?Features
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Creates a new feature.
     *
     * @param array<string, mixed> $data
     * @return Features
     */
    public function create(array $data): Features
    {
        return $this->model->create($data);
    }

    /**
     * Updates a feature.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Features
     */
    public function update(int $id, array $data): Features
    {
        $feature = $this->model->find($id);
        $feature->update($data);

        return $feature;
    }

    /**
     * Deletes a feature.
     *
     * @param int $id
     * @return Features
     */
    public function delete(int $id): Features
    {
        $feature = $this->model->find($id);
        $feature->delete();

        return $feature;
    }
}
