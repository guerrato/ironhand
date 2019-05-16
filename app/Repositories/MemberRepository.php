<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\Contracts\Repository;

class MemberRepository extends Repository
{
    public function __construct(Member $model) 
    {
        $this->model = $model;
    }

    public function getCoordinators() 
    {
        return $this->getModel()
            ->join('member_roles', 'members.role_id', '=', 'member_roles.id')
            ->whereIn('member_roles.slug', ['coordinator', 'administator'])
            ->select('members.*')
            ->get();
    }

    public function getUserByGender($gender) 
    {
        return $this->getModel()->where('gender', $gender)->get();
    }

    public function getById($id = null) 
    {
        return $this->model->findOrFail($id);
    }
}