<?php

namespace App\Http\Controllers;

use Validator;
use App\Helpers\Utils;
use App\Services\Ministry;
use Illuminate\Http\Request;

class MinistryController extends Controller
{
    private $ministry;

    public function __construct(Ministry $ministry) {
        $this->ministry = $ministry;
        $this->rules = $this->ministry->getRules();
    } 

    public function create(Request $request) {

        $validator = Validator::make($request->all(), $this->rules);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }
        
        return $this->formatedSuccess($this->ministry->create($request->all()));

    }

    public function update(Request $request, $id) {

        $data = $request->all();
        $data['id'] = $id;

        $validator = Validator::make($data, Utils::getUpdateRules('ministries', $this->rules, $id));
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->ministry->update($data));

    }

    public function getAll() {
        return $this->formatedSuccess($this->ministry->getAll());
    }
    
    public function getMinistry(Request $request, $id) {
        return $this->formatedSuccess($this->ministry->getMinistry($id));
    }
}
