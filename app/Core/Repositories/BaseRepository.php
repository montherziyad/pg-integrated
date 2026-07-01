<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function all()
    {
        return $this->model->newQuery()->get();
    }

    public function paginate(int $perPage = 20)
    {
        return $this->model
            ->newQuery()
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model
            ->newQuery()
            ->find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->model
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model
            ->newQuery()
            ->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}