<?php

namespace App\Helpers;

use Uuid;

class Utils 
{
    public static function generateUuid() {
        return (string)Uuid::generate();
    }
}