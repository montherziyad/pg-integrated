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
        $data['portal_enabled'] = $data['portal_enabled'] ?? false;
        $clientServiceUserIds = array_slice($data['client_service_user_ids'] ?? [], 0, 3);
        unset($data['password_confirmation'], $data['client_service_user_ids']);

        $client = $this->repository->create($data);
        $client->clientServiceUsers()->sync($clientServiceUserIds);

        return $client;
    }

    public function update(Client $client, array $data): bool
    {
        $data['is_active'] = $data['is_active'] ?? false;
        $data['portal_enabled'] = $data['portal_enabled'] ?? false;
        $clientServiceUserIds = array_slice($data['client_service_user_ids'] ?? [], 0, 3);
        unset($data['password_confirmation'], $data['client_service_user_ids']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $updated = $this->repository->update($client, $data);
        $client->clientServiceUsers()->sync($clientServiceUserIds);

        return $updated;
    }

    public function delete(Client $client): bool
    {
        return $this->repository->delete($client);
    }
}
