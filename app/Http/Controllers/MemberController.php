<?php

namespace App\Http\Controllers;

use Validator;
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

    public function create(Request $request) 
    {
        // return $this->formatedSuccess($request->all());
        $validator = Validator::make($request->all(), $this->rules);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }
        
        return $this->member->create($request->all());
        return $this->formatedSuccess($this->member->create($request->all()));

    }

    public function update(Request $request, $id) 
    {
        $data = $request->all();
        $data['id'] = $id;

        $validator = Validator::make($data, Utils::getUpdateRules('members', $this->rules, $id));
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->member->update($data));

    }

    public function getById(Request $request, $id = null) 
    {
        return $this->formatedSuccess($this->member->getById($id));
    }

    public function getAll()
    {
        return $this->formatedSuccess($this->member->getAll());
    }
}
