
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\{Post, UserRelation, Follow};
    use layouts\post\Post as Post_View;
    use layouts\master_right\Right as MasterRightComponents;

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }

    $welcomeMessage = '';
    if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
        $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
    }


    $current_user_id = $user->getPropertyValue("id");
    $journal_posts = Post::fetch_journal_posts($current_user_id);
 
    shuffle($journal_posts);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAIICT-Community</title>
    <link rel='shortcut icon' type='image/x-icon' href='public/assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="public/css/global.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/index.css">
    <link rel="stylesheet" href="public/css/create-post-style.css">
    <link rel="stylesheet" href="public/css/master-left-panel.css">
    <link rel="stylesheet" href="public/css/master-right-contacts.css">
    <link rel="stylesheet" href="public/css/post.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="public/javascript/config.js" defer></script>
    <script src="public/javascript/index.js" defer></script>
    <script src="public/javascript/global.js" defer></script>
    <script src="public/javascript/master-right.js" defer></script>
    <script src="public/javascript/post.js" defer></script>
</head>
<body>
    <?php include_once "page_parts/basic/header.php"; ?>
    <main>
        <div class="notification-bottom-container">
            <p class="notification-bottom-sentence">THIS IS TEST</p>
        </div>
        <div id="global-container" class="relative">
            <div class="post-viewer-only">
                <div class="viewer-post-wrapper">
                    <img src="" class="post-view-image" alt="">
                    <div class="close-view-post"></div>
                </div>
            </div>
            <?php include_once "page_parts/basic/master-left.php"; ?>
            <div id="master-middle">
                <div class="green-message">
                    <p class="green-message-text"><?php echo $welcomeMessage; ?></p>
                    <script type="text/javascript" defer>
                        if($(".green-message-text").text() !== "") {
                            $(".green-message").css("display", "block");
                        }
                    </script>
                </div>
                <div class="red-message">
                    <p class="red-message-text"></p>
                    <div class="delete-message-hint">
                    </div>
                </div>
                <?php include_once "page_parts/basic/post_creator.php"; ?>
                <div id="posts-container">
                    <?php if(count($journal_posts) == 0) { ?>
                        <div style="color:black" id="empty-posts-message">
                            <h2>Try to add friends, or follow them to see their posts ..</h1>
                            <p>click <a href="/chat/search.php" class="link" style="color: rgb(66, 219, 66)">here</a> to go to the search page</p>
                        </div>
                    <?php } else { 
                        foreach($journal_posts as $post) {
                            $post_view = new Post_View();

                            echo $post_view->generate_post($post, $user);
                        }
                    }
                    ?>

                </div>
            </div>
            <?php include_once "page_parts/basic/master-right.php" ?>
        </div>
    </main>
</body>
</html>