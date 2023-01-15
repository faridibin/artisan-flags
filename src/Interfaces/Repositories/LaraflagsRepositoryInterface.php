<?php

namespace Faridibin\Laraflags\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface LaraflagsRepositoryInterface
{
    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id): ?Model;

    /**
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByName(string $name): ?Model;

    /**
     * @param array<string, mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model;

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function delete(int $id): Model;
}
