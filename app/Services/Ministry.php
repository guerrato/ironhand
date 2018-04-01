<?php

namespace App\Services;

use Exception;
use App\Repositories\MemberRepository;
use App\Repositories\MinistryRepository;

class Ministry 
{

    private $member;
    private $ministry;

    public function __construct(MemberRepository $member, MinistryRepository $ministry)
    {
        $this->member = $member;
        $this->ministry = $ministry;
    }

    public function getRules() 
    {
        return $this->ministry->getRules();
    }

    public function create($data) 
    {
        $coordinators = array_column($this->member->getCoordinators($data['coordinator_id'])->toArray(), 'id');
        
        if(empty($coordinators)) {
            throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
        }
        
        return $this->ministry->create($data);
    }

    public function update($data) 
    {
        $coordinators = array_column($this->member->getCoordinators($data['coordinator_id'])->toArray(), 'id');
        
        if(empty($coordinators)) {
            throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
        }
        
        return $this->ministry->update($data, $data['id']);
    }

    public function getAll() 
    {
        return $this->ministry->all()->load('coordinator');
    }
    
    public function getMinistry($id) 
    {
        return $this->ministry->findOrFail($id);
    }
}
