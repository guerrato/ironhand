<?php

namespace App\Http\Controllers;

use Validator;
use App\Services\MemberRole;
use Illuminate\Http\Request;

class MemberRoleController extends Controller
{
    private $role;

    public function __construct(MemberRole $role) 
    {
        $this->role = $role;
    } 

    public function listRolesByHierarchy(Request $request) 
    {   
        $role_id = $request->role_id;
        return $this->formatedSuccess($this->role->listRolesByHierarchy($role_id));

    }

}
