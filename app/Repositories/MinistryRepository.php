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

    public function getAll(bool $with_coordinators = false)
    {
        $ministries = $this->all();

        if ($with_coordinators === true) {
            $ministries = $ministries->each(function($ministry) {
                $ministry['coordinators'] = $this->getMinistryCoordinators($ministry);
            });
        }

        return $ministries;
    }

    public function getMinistry($id)
    {
        $ministry = $this->findOrFail($id);
        $ministry->coordinators = $this->getMinistryCoordinators($ministry);
        unset($ministry->members);
        return $ministry;
    }

    private function getMinistryCoordinators(Ministry $ministry)
    {
        return $ministry->members()
            ->join('member_roles', 'member_has_roles.role_id', '=', 'member_roles.id')
            ->whereIn('member_roles.slug', ['coordinator', 'administrator'])
            ->select('members.id', 'members.name', 'members.nickname')
            ->get()->each(function($member) {
                unset($member->pivot);
            });
    }

}
