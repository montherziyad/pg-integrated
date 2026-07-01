<?php

namespace App\Core\Contracts;

interface RepositoryInterface
{
    public function all();

    public function find(int $id);

    public function create(array $data);

    public function update(mixed $model, array $data): bool;

    public function delete(mixed $model): bool;
}