<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\GroupRepository;
use App\Repositories\MemberRepository;

class Group
{

    private $group;

    public function __construct(GroupRepository $group, MemberRepository $member)
    {
        $this->group = $group;
        $this->member = $member;
    }

    public function getRules()
    {
        return $this->group->getRules();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $group = $this->group->create($data);
            $group->members()->attach($group->leader_id);

            DB::commit();

            return $group;
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e);
        }
    }

    public function update($data)
    {
        DB::beginTransaction();

        try {
            $stored = $this->getGroup($data['id']);

             $member_gender = $this->member->findOrFail($data['leader_id'])->gender;

            if ($member_gender != $data['required_gender'] && !empty($data['required_gender'])) {
                throw new Exception("The leader gender does not match the group gender.", 1);
            }

            if ($stored->leader_id != $data['leader_id']) {
                $stored->members()->attach($data['leader_id']);
                $stored->members()->detach($stored->leader_id);
            }

            $group = $this->group->update($data, $data['id']);
            DB::commit();

            return $group;
        } catch (Exception $th) {
            DB::rollBack();
            throw new Exception($th, 500);
        }
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

    public function arrangeMember($data)
    {
        DB::beginTransaction();

        try {
            $result = $this->group->arrangeMember($data);

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e);
        }

    }

    public function getMembers($id)
    {
        return $this->group->getMembers($id);
    }

    public function getGroupsOfMinistry($ministry_id)
    {
        return $this->group->getGroupsOfMinistry($ministry_id);
    }
}
