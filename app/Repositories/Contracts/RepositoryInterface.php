<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function getModel();

    public function getRules();

    public function setSlug($value);

    public function create(array $data);
    
    public function update(array $data, $id);
}