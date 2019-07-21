<?php

namespace App\Services;

use Exception;
use App\Helpers\Utils;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\MemberRepository;

class Member
{
    private $utils;
    private $member;

    public function __construct(MemberRepository $member, Utils $utils)
    {
        $this->member = $member;
        $this->utils = $utils;
    }

    public function getRules()
    {
        return $this->member->getRules();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $data['uuid'] = Str::uuid();

            if (!empty($data['image'])) {
                $data['image'] = $this->utils->saveImage($data['image'], 'members');
            }

            $member = $this->member->create($data);

            $member->roles()->attach($data['role_id'], ['ministry_id' => $data['ministry_id']]);
            DB::commit();

            return $member;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update($data)
    {
        DB::beginTransaction();

        try {
            $stored = $this->getById($data['id']);
            $role = $stored->roles()->wherePivot('ministry_id', $data['ministry_id'])->first();

            if (!empty($data['image'])) {
                $data['image'] = $this->utils->saveImage($data['image'], 'members');
                if (!empty($stored->image)) {
                    $this->utils->removeFile($stored->image);
                }
            }

            $member = $this->member->update($data, $data['id']);

            $stored->roles()->wherePivot('ministry_id', $data['ministry_id'])->sync([$data['role_id'] => ['ministry_id' => $data['ministry_id']]]);
            DB::commit();

            return $member;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

    public function delete($id)
    {
        return $this->member->delete($id);
    }

    public function getById($id = null)
    {
        return $this->member->getById($id);
    }

    public function getAll()
    {
        return $this->member->all();
    }

    public function getCoordinators($member_filters = [])
    {
        return $this->member->getCoordinators($member_filters);
    }

    public function getNotAllocatedCoordinators($ministry_id, $member_filters = [])
    {
        return $this->member->getNotAllocatedCoordinators($ministry_id, $member_filters);
    }

    public function getNotAllocatedMembers($ministry_id, $member_filters = [])
    {
        return $this->member->getNotAllocatedMembers($ministry_id, $member_filters);
    }
}
