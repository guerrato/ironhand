<?php

namespace App\Services;

use Exception;
use App\Repositories\UserRepository;
use App\Repositories\MinistryRepository;

class Ministry {

    private $user;
    private $ministry;

    public function __construct(UserRepository $user, MinistryRepository $ministry){
        $this->user = $user;
        $this->ministry = $ministry;
    }

    public function getRules() {
        return $this->ministry->getRules();
    }

    public function create($data) {
        $coordinators = array_column($this->user->getCoordinators($data['coordinator_id'])->toArray(), 'id');
        
        if(empty($coordinators)) {
            throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
        }
        
        return $this->ministry->create($data);
    }

    public function update($data) {
        $coordinators = array_column($this->user->getCoordinators($data['coordinator_id'])->toArray(), 'id');
        
        if(empty($coordinators)) {
            throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
        }
        
        return $this->ministry->update($data, $data['id']);
    }
}
