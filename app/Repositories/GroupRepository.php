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
    
}
