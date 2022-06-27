<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["like_comment_id"])) {

            require_once "../database/pdo.php";
            $comment_id = filter_input(INPUT_POST, "like_comment_id");
            $user_id = $_SESSION["user"]["user_id"];

            // get like where user = user_id and comment = comment_id
            $maRequete = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = :userId AND `comment_id` = :commentId");
            $maRequete->execute([
                "userId" => $user_id,
                ":commentId" => $comment_id
            ]);
            $user_like = $maRequete->fetch();

            // if result, delete like
            if($user_like) {
				// unlike
                $maRequete = $pdo->prepare("DELETE FROM `likes` WHERE `like_id` = :likeId");
                $maRequete->execute([
                    ":likeId" => $user_like["like_id"]
                ]);

                // update number of like of the article
                $maRequete = $pdo->prepare("UPDATE `comments` SET `like_count` = `like_count` -1 WHERE `comment_id` = :commentId");
                $maRequete->execute([
                    ":commentId" => $comment_id
                ]);
				// updating stats of the liker
                $maRequete = $pdo->prepare(
                    "UPDATE `stats`
                    SET `nb_likes` = `nb_likes` - 1
                    WHERE `user_id` = :userId;");
                    $maRequete->execute([
                        ":userId" => $user_id
                    ]);
				// getting the comment's writer's user_id
				$maRequete = $pdo->prepare(
					"SELECT `comment_id`,`user_id`
					FROM `comments`
					WHERE `comment_id` = :comment_id2;");
					$maRequete->execute([
						":comment_id2" => $comment_id
				]);
				$comment = $maRequete->fetch(PDO::FETCH_ASSOC);
				// updating stats of the comment writer
                $maRequete = $pdo->prepare(
                    "UPDATE `stats`
                    SET `likes_on_comments` = `likes_on_comments` - 1
                    WHERE `user_id` = :Id;");
                    $maRequete->execute([
                        ":Id" => $comment['user_id']
                    ]);
            
            } else {
                $name = "un truc";
                require __DIR__ . "/function/uuid.php";
                $uuid = guidv4($name);
				// if no result, add like
                $maRequete = $pdo->prepare("INSERT INTO `likes` (`comment_id`,`user_id`, `uuid`) VALUES(:comment_id, :userId, :uuid)");
                $maRequete->execute([
                    ":comment_id" => $comment_id,
                    ":userId" => $user_id,
                    ":uuid" => $uuid
                ]);

                // update number of like of the article
                $maRequete = $pdo->prepare("UPDATE `comments` SET `like_count` = `like_count` +1 WHERE `comment_id` = :commentId");
                $maRequete->execute([
                    ":commentId" => $comment_id
                ]);
                // updating stats of the liker
                $maRequete = $pdo->prepare(
                    "UPDATE `stats`
                    SET `nb_likes` = `nb_likes` + 1
                    WHERE `user_id` = :userId;");
                    $maRequete->execute([
                        ":userId" => $user_id
                    ]);
				// getting the comment's writer's user_id
				$maRequete = $pdo->prepare(
					"SELECT `comment_id`,`user_id`
					FROM `comments`
					WHERE `comment_id` = :comment_id2;");
					$maRequete->execute([
						":comment_id2" => $comment_id
				]);
				$comment = $maRequete->fetch(PDO::FETCH_ASSOC);
				// updating stats of the comment writer
                $maRequete = $pdo->prepare(
                    "UPDATE `stats`
                    SET `likes_on_comments` = `likes_on_comments` + 1
                    WHERE `user_id` = :Id;");
                    $maRequete->execute([
                        ":Id" => $comment['user_id']
                    ]);
            }

            http_response_code(302);
            // get the previous page
            $direction = explode("/",$_SERVER["HTTP_REFERER"]);
            // go to the previous page
            if($direction[3] === "profile") {
                header('Location: /profile');
            } else if ($direction[3] === "timeline") {
				header("Location: /timeline");
			} else if ($direction[3] === "public_page") {
				header("Location: /public_page");
			} else if ($direction[3] === "group") {
				header("Location: /group");
			}
            exit();
        }
    }

?>