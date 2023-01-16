<?php

use classes\{Cookie, DB, Config, Session};
use models\User;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$GLOBALS["config"] = array(
    "mysql" => array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'db'=>'chat'
    ),
    "remember"=> array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    "session"=>array(
        'session_name'=>'user',
        "token_name" => "token",
        "tokens"=>array(
            "register"=>"register",
            "login"=>"login",
            "reset-pasword"=>"reset-pasword",
            "saveEdits"=>"saveEdits",
            "share-post"=>"share-post",
            "logout"=>"logout"
        )
    ),
    "root"=> array(
        'path'=>'/chat/',
        'project_name'=>"CHAT"
    )
);


$root = Config::get("root/path");
$proj_name = Config::get("root/project_name");

$user = new User();

if(Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))) {
    $hash = Cookie::get(Config::get("remember/cookie_name"));
    $res = DB::getInstance()->query("SELECT * FROM users_session WHERE hash = ?", array($hash));

    if($res->count()) {
        $user->fetchUser("id", $res->results()[0]->user_id);
        $user->login($user->getPropertyValue("username"),$user->getPropertyValue("password"),true);
    }
}

if($user->getPropertyValue("isLoggedIn")) {
    $user->update_active();
}

