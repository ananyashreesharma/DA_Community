<?php

namespace classes;

class Token {
    
    public static function generate($type) {
        return Session::put(Config::get("session/tokens/$type"), md5(uniqid()));
    }

    public static function check($token, $type) {
        $tokenName = Config::get("session/tokens/$type");

        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}