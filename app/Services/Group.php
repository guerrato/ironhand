<?php

namespace App\Services;

use Exception;
use App\Repositories\GroupRepository;

class Group 
{

    private $group;

    public function __construct(GroupRepository $group)
    {
        $this->group = $group;
    }

    public function getRules() 
    {
        return $this->group->getRules();
    }

    public function create($data) 
    {
        return $this->group->create($data);
    }

    public function update($data) 
    {
        return $this->group->update($data, $data['id']);
    }

    public function delete($id) 
    {
        return $this->group->delete($id);
    }

    public function getAll() 
    {
        return $this->group->all();
    }
    
    public function getGroup($id) 
    {
        return $this->group->findOrFail($id);
    }

    public function arrageMembers($data) 
    {
        return $this->group->arrageMembers($data);
    }
    
    public function getMembers($id) 
    {
        return $this->group->getMembers($id);
    }
}
