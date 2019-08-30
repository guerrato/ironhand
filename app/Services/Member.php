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

            $member->ministries()->attach($data['ministry_id'], ['role_id' => $data['role_id']]);
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

            if (!empty($data['image'])) {
                $data['image'] = $this->utils->saveImage($data['image'], 'members');
                if (!empty($stored->image)) {
                    $this->utils->removeFile($stored->image);
                }
            }

            $result = $this->member->update($data, $data['id']);

            $role = $stored->ministries()->wherePivot('ministry_id', $data['ministry_id'])->first();
            if (!empty($role)) {
                $stored->ministries()->updateExistingPivot($data['ministry_id'], ['role_id' => $data['role_id']]);
            } else {
                $stored->ministries()->attach($data['ministry_id'], ['role_id' => $data['role_id']]);
            }
            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete($data)
    {
        DB::beginTransaction();

        try {
            $stored = $this->getById($data['id']);
            $stored->ministries()->wherePivot('ministry_id', $data['ministry_id'])->detach();
            $roles = $stored->ministries()->get();

            $result = true;

            if (empty($roles->toArray())) {
                $result = $this->member->delete($data['id']);
            }

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getById($ministry_id, $id = null)
    {
        $member = $this->member->getById($id);
        $role = $member->roles()->wherePivot('ministry_id', $ministry_id)->first();
        if (!empty($role)) {
            $member->role_id = $role->id;
        }

        return $member;
    }

    public function getAll($ministry_id, $no_filter = false)
    {
        return $this->member->getAll($ministry_id, $no_filter);
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

    public function getSimilarSearch($q)
    {
        $q = $this->utils->normalizeString($q);
        $members = [];
        $stored = $this->getAll(0, true);
        $words = [];

        foreach ($stored as $m) {
            foreach (explode(' ', $m->name) as $word) {
                $words[$word] = 0;
            }

            foreach (explode(' ', $m->nickname) as $word) {
                $words[$word] = 0;
            }
        }

        // Get similarity of strings
        foreach ($words as $word => $value) {
            $words[$word] = $this->utils->getSimilarity($q, $this->utils->normalizeString($word))['percentage'];
            if ($words[$word] < 36) {
                unset($words[$word]);
            }
        }

        // Search the members into the similarity range
        $found = $this->member->getSimilarSearch($words);

        // Generate result withou duplivity of registers
        $result = [];
        foreach ($words as $word => $percentage) {
            foreach ($found as $m) {
                $key = array_search($m->id, array_column($result, 'id'));
                if (strpos($m->name, $word) !== false || strpos($m->nickname, $word) !== false) {
                    if($key === false) {
                        $m->percentage = $percentage;
                        $result[] = $m;
                    } else {
                        if ($result[$key]->percentage < $percentage) {
                            $result[$key]->percentage = $percentage;
                        }
                    }
                }
            }
        }

        return $result;

    }
}
