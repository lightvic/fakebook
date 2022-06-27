<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["comment_id"])) {
            // get info from form
            require "../database/pdo.php";
            $comment_id = filter_input(INPUT_POST, "comment_id");
            $comment_user = filter_input(INPUT_POST, "comment_user");
            $user_id = $_SESSION["user"]["user_id"];
            $group = $_SESSION["group"];
            $group_id = $_SESSION["group"]["group_id"];
            $page = $_SESSION["page"];
            $page_id = $_SESSION["page"]["page_id"];
            // if user_id = comment_id, i can delete the comment
            if($comment_user === $user_id || $group_id !== NULL || $page_id !== NULL) {
                if($comment_id) {
                    // delete comment
                    $maRequete = $pdo->prepare("DELETE FROM `comments` WHERE `comment_id` = :id");
                    $maRequete->execute([
                        ":id" => $comment_id
                    ]);
					// updating stats of the comment's writer
                    $maRequete = $pdo->prepare(
						"UPDATE `stats`
						SET `nb_comments` = `nb_comments` - 1
						WHERE `user_id` = :userId;");
						$maRequete->execute([
							":userId" => $user_id
						]);
                    // go to last location
                    http_response_code(302);
                    $direction = explode("/",$_SERVER["HTTP_REFERER"]);
                    if($direction[3] === "profile") {
                        header("Location: /profile");
                    } else if ($direction[3] === "timeline") {
						header("Location: /timeline");
					} else if ($direction[3] === "public_page") {
						header("Location: /public_page");
					}
                    else if ($direction[3] === "group") {
                        header("Location: /group");
                    }
                    exit();
                }
            } else {
                $message = "cet comment n'est pas de vous"; 
                http_response_code(403);
                require_once __DIR__ . "/../html_partial/alert/baniere.php";
            }
        }
    }
?>