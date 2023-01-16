<?php


require_once "../../../vendor/autoload.php";
require_once "../../../core/rest_init.php";

use classes\Config;
use models\{User, Message};
use layouts\chat\ChatComponent;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../../functions/sanitize_id.php";



if(!isset($_POST["user_id"])) {
    echo "";
}

$current_user_id = sanitize_id($_POST["user_id"]);

$discussions = Message::get_discussions($current_user_id);
$temp = array();
$result = array();


foreach($discussions as $discussion) {

    $current_disc = array(
        "sender"=>$discussion->message_receiver,
        "receiver"=>$discussion->message_creator
    );

    if(in_array($current_disc, $temp)) {
        continue; 
    }

    $temp[] = array(
        "sender"=>$discussion->message_creator,
        "receiver"=>$discussion->message_receiver
    );
    $result[] = $discussion;
}

$content = '';
foreach($result as $discussion) {
    $chat_comp = new ChatComponent();

    $content .= $chat_comp->generate_discussion($current_user_id, $discussion);
}

echo $content;
