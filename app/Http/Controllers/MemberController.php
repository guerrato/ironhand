<?php

namespace App\Http\Controllers;

use Validator;
use Exeception;
use App\Helpers\Utils;
use App\Services\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
        $this->rules = $this->member->getRules();
    }

    public function create(Request $request, $ministry_id)
    {
        $data = $request->all();
        $data['ministry_id'] = $ministry_id;

        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->create($data));
    }

    public function update(Request $request, $ministry_id, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['ministry_id'] = $ministry_id;

        $validator = Validator::make($data, Utils::getUpdateRules('members', $this->rules, $id));

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->update($data));

    }

    public function delete(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|exists:members,id']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->delete($id));
    }

    public function getById(Request $request, $id = null)
    {
        return $this->formatedSuccess($this->member->getById($id));
    }

    public function getAll()
    {
        return $this->formatedSuccess($this->member->getAll());
    }

    public function getCoordinators(Request $request, $ministry_id)
    {
        $validator = Validator::make(['ministry_id' => $ministry_id], ['ministry_id' => 'required|exists:ministries,id']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->getCoordinators($ministry_id, $request->all()));
    }

    public function getNotAllocatedCoordinators(Request $request, $ministry_id)
    {
        $validator = Validator::make(['ministry_id' => $ministry_id], ['ministry_id' => 'required|exists:ministries,id']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->getNotAllocatedCoordinators($ministry_id, $request->all()));
    }

    public function getNotAllocatedMembers(Request $request, $ministry_id)
    {
        $validator = Validator::make(['ministry_id' => $ministry_id], ['ministry_id' => 'required|exists:ministries,id']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->getNotAllocatedMembers($ministry_id, $request->all()));
    }
}
