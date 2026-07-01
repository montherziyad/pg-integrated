<?php

namespace App\Modules\Users\Repositories;

use App\Models\User;

class UserRepository
{
    public function all()
    {
        return User::with(['branch', 'team', 'role'])
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): ?User
    {
        return User::with(['branch', 'team', 'role'])->find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}