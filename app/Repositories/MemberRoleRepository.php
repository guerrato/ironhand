<?php

namespace App\Repositories;

use App\Models\MemberRole;
use App\Repositories\Contracts\Repository;

class MemberRoleRepository extends Repository 
{
    
    public function __construct(MemberRole $model) 
    {
        $this->model = $model;
    }

    /**
     * Based on the Member (Logged user) role id, it returnes a list of possible member hierarchy.
     *
     * @return array
     */
    public function listRolesByHierarchy($role_id = 1)
    {
        $available_roles = [];

        switch ($role_id) {
            case 3:
                $available_roles = [1,2,3];
                break;
            case 2:
                $available_roles = [1,2];
                break;
            default:
                $available_roles = [];
                break;
        }

        return $this->model->whereIn('id', $available_roles)->get();
    }
}
