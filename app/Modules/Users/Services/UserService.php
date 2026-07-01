<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?User
    {
        return $this->repository->find($id);
    }

    public function create(array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_active'] = $data['is_active'] ?? true;

        return $this->repository->create($data);
    }

    public function update(User $user, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->repository->update($user, $data);
    }

    public function delete(User $user): bool
    {
        return $this->repository->delete($user);
    }
}