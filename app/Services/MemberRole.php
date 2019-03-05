<?php

namespace App\Services;

use Exception;
use App\Repositories\MemberRoleRepository;

class MemberRole
{
    private $role;

    public function __construct(MemberRoleRepository $role)
    {
        $this->role = $role;
    }
    
    public function listRolesByHierarchy($role_id = 1)
    {
        return $this->role->listRolesByHierarchy($role_id);
    }
}
