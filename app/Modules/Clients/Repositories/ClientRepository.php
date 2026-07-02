<?php

namespace App\Modules\Clients\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function all()
    {
        return Client::with(['branch', 'accountManager'])
            ->withCount('projects')
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?Client
    {
        return Client::with([
            'branch',
            'accountManager',
            'projects',
        ])->find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): bool
    {
        return $client->update($data);
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }
}
