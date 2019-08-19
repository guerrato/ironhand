<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\GroupRepository;
use App\Repositories\Contracts\Repository;

class MemberRepository extends Repository
{
    public function __construct(Member $model, GroupRepository $group)
    {
        $this->model = $model;
        $this->group = $group;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $member_filters that only accepts filters keys with the same names of model attributes
     * @return App\Models\Member
     */
    public function getCoordinators($ministry_id, $member_filters = [])
    {
        $fillable = $this->model->getFillable();

        $coords = $this->getModel()
            ->join('member_has_roles', 'members.id', '=', 'member_has_roles.member_id')
            ->join('member_roles', 'member_has_roles.role_id', '=', 'member_roles.id')
            ->where('member_has_roles.ministry_id', $ministry_id)
            ->whereIn('member_roles.slug', ['leader', 'coordinator', 'administrator']);

        foreach ($member_filters as $key => $value) {
            if (in_array($key, $fillable)) {
                $coords = $coords->where("members.$key", $value);
            }
        }

        return $coords->select('members.*')->get();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  integer  $ministry_id
     * @param  array  $member_filters that only accepts filters keys with the same names of model attributes
     * @return App\Models\Member
     */
    public function getNotAllocatedCoordinators($ministry_id, $member_filters = [])
    {
        $fillable = $this->model->getFillable();

        $this->allocated = [];
        $allocated = [];
        $this->group->getGroupsOfMinistry($ministry_id)->each(function($grp) {
            $this->allocated[] = $grp->leader_id;
            $this->allocated = array_merge($this->allocated, array_column($grp->members->toArray(), 'id'));
        });

        $allocated = array_unique($this->allocated);
        unset($this->allocated);

        $coords = $this->getModel()
            ->join('member_has_roles', 'members.id', '=', 'member_has_roles.member_id')
            ->join('member_roles', 'member_has_roles.role_id', '=', 'member_roles.id')
            ->where('member_has_roles.ministry_id', $ministry_id)
            ->whereIn('member_roles.slug', ['leader', 'coordinator', 'administrator']);

        foreach ($member_filters as $key => $value) {
            if (in_array($key, $fillable)) {
                $coords = $coords->where("members.$key", $value);
            }
        }

        return $coords->whereNotIn('members.id', $allocated)->select('members.*')->orderBy('members.id')->get();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  integer  $ministry_id
     * @param  array  $member_filters that only accepts filters keys with the same names of model attributes
     * @return App\Models\Member
     */
    public function getNotAllocatedMembers($ministry_id, $member_filters = [])
    {
        $fillable = $this->model->getFillable();

        $members = $this->getModel()
            ->whereNotIn('members.id', function($query) use ($ministry_id) {
                $query->select('members_in_group.member_id')
                    ->from('members_in_group')
                    ->join('groups', 'members_in_group.group_id', '=', 'groups.id')
                    ->where('groups.ministry_id', $ministry_id);
            });

        foreach ($member_filters as $key => $value) {
            if (in_array($key, $fillable)) {
                $members = $members->where("members.$key", $value);
            }
        }

        return $members->select('members.*')->orderBy('members.id')->get();
    }

    public function getUserByGender($gender)
    {
        return $this->getModel()->where('gender', $gender)->get();
    }

    public function getById($id = null)
    {
        return $this->findOrFail($id);
    }
}
