<?php

namespace App\Services;

use Exception;
use App\Repositories\MemberStatusRepository;

class MemberStatus
{
    private $status;

    public function __construct(MemberStatusRepository $status)
    {
        $this->status = $status;
    }
    
    public function getAll()
    {
        return $this->status->all();
    }
}
