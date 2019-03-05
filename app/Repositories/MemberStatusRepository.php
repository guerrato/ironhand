<?php

namespace App\Repositories;

use App\Models\MemberStatus;
use App\Repositories\Contracts\Repository;

class MemberStatusRepository extends Repository 
{
    public function __construct(MemberStatus $model) 
    {
        $this->model = $model;
    }

}
