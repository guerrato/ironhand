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
    public function getCoordinators($member_filters = []) 
    {
        $fillable = $this->model->getFillable();

        $coords = $this->getModel()
            ->join('member_roles', 'members.role_id', '=', 'member_roles.id')
            ->whereIn('member_roles.slug', ['coordinator', 'administrator']);
        
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

        $groups_leader = array_column($this->group->getGroupsOfMinistry($ministry_id)->toArray(), 'leader_id');

        $coords = $this->getModel()
            ->join('member_roles', 'members.role_id', '=', 'member_roles.id')
            ->whereIn('member_roles.slug', ['coordinator', 'administrator']);
            
        foreach ($member_filters as $key => $value) {
            if (in_array($key, $fillable)) {
                $coords = $coords->where("members.$key", $value);
            }
        }

        return $coords->whereNotIn('members.id', $groups_leader)->select('members.*')->orderBy('members.id')->get();
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
        return $this->model->findOrFail($id);
    }
}