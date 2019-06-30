<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\Repository;

class GroupRepository extends Repository
{

    public function __construct(Group $model)
    {
        $this->model = $model;

    }

    public function create(array $data)
    {
        $data['slug'] = $this->setSlug($data['description']);
        return parent::create($data);

    }

    public function update(array $data, $id)
    {
        $data['slug'] = $this->setSlug($data['description'], $data['id']);
        return parent::update($data, $id);

    }

    public function delete($id)
    {
        $group = $this->findOrFail($id);
        $group->members()->detach();

        parent::delete($id);
    }


    public function arrangeMember($data)
    {
        $group = $this->findOrFail($data['group_id']);

        $members = array_unique(array_merge([$group->leader_id], $data['members']));

        return $group->members()->sync($members);
    }

    public function getMembers($id)
    {
        return $this->findOrFail($id)->members;
    }

    public function getGroupsOfMinistry($ministry_id)
    {
        return $this->model
            ->where('ministry_id', $ministry_id)
            ->with('leader')
            ->get();
    }
}
