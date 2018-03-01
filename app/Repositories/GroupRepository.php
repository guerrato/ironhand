<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\Repository;

class GroupRepository extends Repository 
{
    
    public function __construct(Group $model) {
        $this->model = $model;

    }

    public function create(array $data) {
        $data['slug'] = $this->setSlug($data['description']);
        return parent::create($data);

    }
    
    public function update(array $data, $id) {
        $data['slug'] = $this->setSlug($data['description'], $data['id']);
        return parent::update($data, $id);

    }

    public function delete($id) {
        $group = $this->findOrFail($id);
        $group->members()->detach();

        parent::delete($id);
    }


    public function arrageMembers($data) {
        $group = $this->findOrFail($data['group_id']);
        $group->members()->detach();

        return $group->members()->attach($data['members']);
    }

    public function getMembers($id) {
        return $this->findOrFail($id)->members;
    }
    
}
