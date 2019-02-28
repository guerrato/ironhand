<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use App\Repositories\MemberRepository;

class Member 
{
    private $member;

    public function __construct(MemberRepository $member)
    {
        $this->member = $member;
    }

    public function getRules() 
    {
        return $this->member->getRules();
    }

    public function create($data) 
    {
        $data['uuid'] = Str::uuid();
        return $this->member->create($data);
    }

    public function update($data) 
    {
        return $this->member->update($data, $data['id']);
    }

    public function getAll() 
    {
        return $this->member->all();
    }
}
