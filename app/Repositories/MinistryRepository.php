<?php

namespace App\Repositories;

use App\Models\Ministry;
use App\Repositories\Contracts\Repository;

class MinistryRepository extends Repository 
{
    
    public function __construct(Ministry $model) 
    {
        $this->model = $model;

    }

    public function create(array $data) 
    {
        $data['slug'] = $this->setSlug($data['name']);
        return parent::create($data);

    }
    
    public function update(array $data, $id) 
    {
        $data['slug'] = $this->setSlug($data['name'], $data['id']);
        return parent::update($data, $id);

    }
    
}
