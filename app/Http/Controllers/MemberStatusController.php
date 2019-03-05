<?php

namespace App\Http\Controllers;

use Validator;
use App\Services\MemberStatus;
use Illuminate\Http\Request;

class MemberStatusController extends Controller
{
    private $status;

    public function __construct(MemberStatus $status) 
    {
        $this->status = $status;
    } 

    public function getAll() 
    {   
        return $this->formatedSuccess($this->status->getAll());

    }

}
