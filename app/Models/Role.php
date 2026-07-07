<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected function casts(): array
    {
        return [
            'screen_permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected $fillable = [
        'name',
        'code',
        'description',
        'screen_permissions',
        'is_active',
    ];

    public function enabledScreenPermissions(): array
    {
        return $this->screen_permissions ?: \App\Support\RoleScreenPermissions::defaultsForRole($this->code);
    }

    public function hasScreenPermission(string $screen): bool
    {
        if (in_array($this->code, \App\Support\RoleScreenPermissions::ADMIN_ROLE_CODES, true)) {
            return true;
        }

        return (bool) data_get($this->enabledScreenPermissions(), $screen, false);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
