<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\GroupRepository;

class Group
{

    private $group;

    public function __construct(GroupRepository $group)
    {
        $this->group = $group;
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

            if ($stored->leader_id != $data['leader_id']) {
                $stored->members()->attach($data['leader_id']);
                $stored->members()->detach($stored->leader_id);
            }

            $group = $this->group->update($data, $data['id']);
            DB::commit();

            return $group;
        } catch (Exception $th) {
            DB::rollBack();
            return new Exception($th);
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
