<?php

namespace App\Traits;

use App\Enums\Role;

trait RoleAbilities
{
    public function isRole(bool $isAdmin = true): bool
    {
        return
            auth()->check() &&
            in_array(
                $this->group->value(),
                $isAdmin
                    ? Role::getAdminList()
                    : Role::getWorkerGroup()
            );
    }

}
