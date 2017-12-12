<?php

namespace App\Http\Controllers;

use Validator;
use App\Helpers\Utils;
use App\Services\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $group;

    public function __construct(Group $group) {
        $this->group = $group;
        $this->rules = $this->group->getRules();
    } 

    public function create(Request $request, $ministry_id) {

        $data = $request->all();
        $data['ministry_id'] = $ministry_id;

        $validator = Validator::make($data, $this->rules);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }
        
        return $this->formatedSuccess($this->group->create($data));

    }

    public function update(Request $request, $ministry_id, $id) {

        $data = $request->all();
        $data['ministry_id'] = $ministry_id;
        $data['id'] = $id;

        $validator = Validator::make($data, Utils::getUpdateRules('groups', $this->rules, $id));
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->group->update($data));

    }

    public function getAll() {
        return $this->formatedSuccess($this->group->getAll());
    }
    
    public function getGroup(Request $request, $ministry_id, $id) {
        return $this->formatedSuccess($this->group->getGroup($id));
    }
}
