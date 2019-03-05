<?php

namespace App\Services;

use Exception;
use App\Helpers\Utils;
use Illuminate\Support\Str;
use App\Repositories\MemberRepository;

class Member 
{
    private $utils;
    private $member;

    public function __construct(MemberRepository $member, Utils $utils)
    {
        $this->member = $member;
        $this->utils = $utils;
    }

    public function getRules() 
    {
        return $this->member->getRules();
    }

    public function create($data) 
    {
        $data['uuid'] = Str::uuid();

        if (!empty($data['image'])) {
            $data['image'] = $this->utils->saveImage($data['image'], 'members');
        }
        
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
