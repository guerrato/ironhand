<?php

namespace App\Repositories;

use App\User;
use App\Repositories\Contracts\Repository;

class UserRepository extends Repository
{
    public function __construct(User $model) {
        $this->model = $model;
    }

    public function getCoordinators() {
        return $this->getModel()
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('roles.slug', ['coordinator', 'administator'])
            ->select('users.*')
            ->get();
    }
}