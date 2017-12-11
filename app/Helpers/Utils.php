<?php

namespace App\Helpers;

use Uuid;

class Utils 
{
    public static function generateUuid() {
        return (string)Uuid::generate();
    }

    public static function getUpdateRules($tableName, array $rules, $id) {
        foreach ($rules as $key => $value) {
            if (strpos($value, 'unique:') !== false) {
                $ruleValue = '';
                $validations = explode("|", $value);

                foreach ($validations as $vl) {
                    if(strpos($vl, 'unique:') !== false) {
                        $un = explode(",", $vl);
                        $ruleValue .= sprintf('%s,%s,%d|', $un[0], $un[1], $id);
                    } else {
                        $ruleValue .= $vl . '|';
                    }
                }

                $rules[$key] = $ruleValue;
            }
        }

        $rules['id'] = 'required|exists:' . $tableName . ',id';

        foreach ($rules as $key => $value) {
            if(substr($value, -1) === '|') {
                $rules[$key] = substr($value, 0, -1);
            }
        }

        return $rules;
    }
}