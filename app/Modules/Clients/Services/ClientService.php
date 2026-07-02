<?php

namespace App\Modules\Clients\Services;

use App\Models\Client;
use App\Modules\Clients\Repositories\ClientRepository;

class ClientService
{
    public function __construct(
        protected ClientRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Client
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Client
    {
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    public function update(Client $client, array $data): bool
    {
        $data['is_active'] = $data['is_active'] ?? false;

        return $this->repository->update($client, $data);
    }

    public function delete(Client $client): bool
    {
        return $this->repository->delete($client);
    }
}
