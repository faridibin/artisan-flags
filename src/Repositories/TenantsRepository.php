<?php

namespace Faridibin\Laraflags\Repositories;

use Illuminate\Database\Eloquent\Model;

class TenantsRepository
{
    /**
     * The tenant model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     */
    public function __construct()
    {
        $this->model = app()->make(config('laraflags.tenancy.model'));
    }

    /**
     * Gets a tenant by id.
     *
     * @param int $id
     * @return Model|null
     */
    public function getById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Gets a tenant by name.
     *
     * @param string $name
     * @return Features|null
     */
    public function getByName(string $name): ?Model
    {
        return $this->model->where('name', $name)->first();
    }
}
