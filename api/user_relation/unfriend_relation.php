<?php


require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use models\{User, UserRelation};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";

$current_user = sanitize_id($_POST["current_user_id"]);
$friend = sanitize_id($_POST["current_profile_id"]);


if($current_user === $friend) {
    echo json_encode(
        array(
            "message"=>"You can't unfriend yourself",
            "success"=>false
        )
    );

    exit();
}


if(($current_user) && 
    User::user_exists("id", $current_user)) {
       
        if($friend && 
            User::user_exists("id", $friend)) {
            
            $user_relation = new UserRelation();

            $user_relation->set_property("from", $current_user);
            $user_relation->set_property("to", $friend);

            if($user_relation->unfriend()) {
                echo json_encode(
                    array(
                        "message"=>"user with id: $current_user unfriends user with id: $friend successfully",
                        "success"=>true,
                        "error"=>false
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "message"=>"user with id: $current_user is not a friend of user with id: $friend or some invalide data is provided",
                        "success"=>false,
                        "error"=>false
                    )
                );
            }

        } else {
            echo json_encode(
                array(
                    "message"=>"friend's id is either not valid or not exists in our db",
                    "success"=>false,
                    "error"=>true
                )
            );
        }
} else {
    echo json_encode(
        array(
            "message"=>"your id is either not valid or not exists in our db",
            "success"=>false,
            "error"=>true
        )
    );
}