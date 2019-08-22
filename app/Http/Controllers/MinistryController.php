<?php

namespace App\Http\Controllers;

use Validator;
use App\Helpers\Utils;
use App\Services\Ministry;
use Illuminate\Http\Request;

class MinistryController extends Controller
{
    private $ministry;

    public function __construct(Ministry $ministry)
    {
        $this->ministry = $ministry;
        $this->rules = $this->ministry->getRules();
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->ministry->create($request->all()));

    }

    public function update(Request $request, $id)
    {

        $data = $request->all();
        $data['id'] = $id;

        $validator = Validator::make($data, Utils::getUpdateRules('ministries', $this->rules, $id));

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->ministry->update($data));

    }

    public function delete(Request $request, $id)
    {

        $validator = Validator::make(['id' => $id], ['id' => 'required|exists:ministries,id']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 500);
        }

        return $this->formatedSuccess($this->ministry->delete($id));
    }

    public function getAll(Request $request)
    {
        $with_coordinators = false;

        if (!empty($request->with_coordinators))
        {
            $with_coordinators = filter_var($request->with_coordinators, FILTER_VALIDATE_BOOLEAN);
        }

        return $this->formatedSuccess($this->ministry->getAll($with_coordinators));
    }

    public function getMinistry(Request $request, $id)
    {
        return $this->formatedSuccess($this->ministry->getMinistry($id));
    }
}
