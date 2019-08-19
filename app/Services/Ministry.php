<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\MemberRepository;
use App\Repositories\MinistryRepository;

class Ministry
{

    private $member;
    private $ministry;

    public function __construct(MemberRepository $member, MinistryRepository $ministry)
    {
        $this->member = $member;
        $this->ministry = $ministry;
    }

    public function getRules()
    {
        return $this->ministry->getRules();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $ministry = $this->ministry->create($data);
            $ministry->members()->attach(1, ['role_id' => 3]);

            DB::commit();

            return $ministry;
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage(), 500);
        }
    }

    public function update($data)
    {
        DB::beginTransaction();

        try {
            if (empty($data['coordinators'])) {
                throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
            }

            $ministry = $this->getMinistry($data['id']);
            $coordinators = array_column($ministry->coordinators->toArray(), 'id');
            $newCoordinators = [];

            $this->ministry->update($data, $data['id']);

            $coordinators = array_merge($coordinators);

            foreach ($coordinators as $key) {
                $newCoordinators[$key] = ['role_id' => 1];
            }

            foreach ($data['coordinators'] as $key) {
                $newCoordinators[$key] = ['role_id' => 3];
            }

            $ministry->members()->syncWithoutDetaching($newCoordinators);

            DB::commit();
            $ministry = $this->getMinistry($data['id']);

            return $ministry;
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage(), 500);
        }

        $coordinators = array_column($this->member->getCoordinators($data['coordinator_id'])->toArray(), 'id');

        if(empty($coordinators)) {
            throw new Exception(__('messages.user.coordinator.no_coordinator'), 1);
        }

        return $this->ministry->update($data, $data['id']);
    }

    public function delete($id)
    {
        return $this->ministry->delete($id);
    }

    public function getAll()
    {
        return $this->ministry->getAll();
    }

    public function getMinistry($id)
    {
        return $this->ministry->getMinistry($id);
    }
}
