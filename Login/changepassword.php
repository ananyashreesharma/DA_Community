<?php

    require_once "../vendor/autoload.php";
    require_once "../core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect};
    use models\User;

    
    if(!Session::exists("password-change-allow")) {
        Redirect::to("login.php");
    }

    $validate = new Validation();
    
   
    Session::delete("Password_changed");

    $user->fetchUser("id", Session::get("u_id"));

    if(isset($_POST["save"])) {
        if(Token::check(Common::getInput($_POST, "token_password_save"), "reset-pasword")) {
            $validate->check($_POST, array(
                "password"=>array(
                    "name"=>"Password",
                    "required"=>true,
                    "min"=>6
                  
                ),
                "password_again"=>array(
                    "name"=>"Repeated password",
                    "required"=>true,
                    "matches"=>"password"
                )
            ));

            if($validate->passed()) {
                if(Common::getInput($_POST, "email") != $user->getPropertyValue("email")) {
                    $validate->addError("It seems that you change the email section which is not allowed !");
                } else {
                  
                    $newSalt = Hash::salt(16);
                    $newPassword = Hash::make(Common::getInput($_POST, "password"), $newSalt);

                    
                    $user->setPropertyValue("password", $newPassword);
                    $user->setPropertyValue("salt", $newSalt);
                    
                    $user->update();

                    
                    Session::flash("Password_changed", "Your password has been changed successfully.");
                }
            }

            foreach($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password recovery</title>
    <link rel='shortcut icon' type='image/x-icon' href='../public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/log-header.css">
    <style>
        #reset-section {
            padding: 20px;
            width: 340px;
        }
    </style>
</head>
<body>
    <?php include "../page_parts/basic/log-header.php" ?>
    <main>
        <div id="reset-section">
            <div class="green-message">
                <p class="green-message-text"><?php $changed = Session::flash("Password_changed"); echo $changed;?></p>
            </div>
            <h2 class="title-style1">New Password</h2>
            <p>Choose a new password</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="flex-column">
                <div class="classic-form-input-wrapper">
                    <label for="email" class="classic-label">Your Email</label>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($user->getPropertyValue("email")); ?>" placeholder="Email address" autocomplete="off" class="classic-input">
                </div>
                <div class="classic-form-input-wrapper">
                    <label for="password" class="classic-label">New Password</label>
                    <input type="password" name="password" autofocus placeholder="Password" tabindex="1" autocomplete="off" class="classic-input">
                </div>
                <div class="classic-form-input-wrapper">
                    <label for="password_again" class="classic-label">Re-enter the new password</label>
                    <input type="password" name="password_again" placeholder="Re-enter password" tabindex="2" autocomplete="off" class="classic-input">
                </div>
                <div class="classic-form-input-wrapper">
                    <input type="hidden" name="token_password_save" value="<?php echo Token::generate("reset-pasword"); ?>">
                    <input type="submit" value="Save" name="save" tabindex="3" class="button-style-1" style="width: 70px;">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
