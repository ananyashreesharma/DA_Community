<?php

namespace classes;

class Common {
    public static function getInput($source, $fieldName) {
        if(isset($source[$fieldName])) {
            return $source[$fieldName];
        }

        return '';
    }


    public static function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
    
        foreach($array as $val) {
            if (!in_array($val->getPropertyValue($key), $key_array)) {
                $key_array[$i] = $val->getPropertyValue($key);
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}