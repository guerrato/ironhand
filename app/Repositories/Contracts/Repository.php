<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    protected $model;

    public function getModel() 
    {
        return $this->model;
    }

    public function getRules() 
    {
        
        if(!empty($this->model->rules)) {
            return $this->model->rules;
        }
        
        return [];
    }

    public function setSlug($value, $id = 0) 
    {
        $slug = str_slug($value, '-');

        $existent = $this->model
            ->where('slug', $slug)
            ->where('id', '<>', $id)
            ->count();

        if($existent > 0) {
            return $slug . '-' . time();
        }

        return $slug;
    }

    public function all() 
    {
        return $this->model->all();
    }
    
    public function findOrFail($id) 
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data) 
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id) 
    {
        $model = $this->model->findOrFail($id);
        return $model->update($data);
    }

    public function delete($id) 
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }
}