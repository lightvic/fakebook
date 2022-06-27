<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["commentInput"])) {
            require_once __DIR__ . "/../database/pdo.php";
            $text = filter_input(INPUT_POST, "commentInput");
            $article_id = filter_input(INPUT_POST, "article_id");
            $user_id = $_SESSION["user"]["user_id"];


            $name = "un truc";
			require __DIR__ . "/function/uuid.php";
			$uuid = guidv4($name);
			// 1st we create the comment in the comment table
            $maRequete = $pdo->prepare(
                "INSERT INTO `comments` (`content`, article_id, `user_id`, `uuid`)
                VALUES(:content, :article_id, :userId, :uuid)");
                $maRequete->execute([
                    ":content" => $text,
                    ":article_id" => $article_id,
                    ":userId" => $user_id,
					":uuid" => $uuid
                ]);
			// updating stats of the commenter
			$maRequete = $pdo->prepare(
				"UPDATE `stats`
				SET `nb_comments` = `nb_comments` + 1
				WHERE `user_id` = :userId2;");
				$maRequete->execute([
					":userId2" => $user_id
				]);


			// getting the article's writer's user_id
			$maRequete = $pdo->prepare(
				"SELECT `article_id`,`user_id`
				FROM `articles`
				WHERE `article_id` = :article_id2;");
				$maRequete->execute([
					":article_id2" => $article_id
				]);
			$article = $maRequete->fetch(PDO::FETCH_ASSOC);


			$maRequete = $pdo->prepare(
				"UPDATE `stats`
				SET `comments_on_articles` = `comments_on_articles` + 1
				WHERE `user_id` = :id;");
				$maRequete->execute([
					":id" => $article['user_id']
				]);

			$maRequete = $pdo->prepare(
				"SELECT `comment_id`
				FROM `comments`
				WHERE `uuid` = :uuid;");
			$maRequete->execute([
				":uuid" => $uuid
			]);
			$comment_id = $maRequete->fetch();
			// updating notification of the article writer
			$maRequete = $pdo->prepare(
				"INSERT INTO `notifications` (`type`, `seen`, `user_id`, `comment_id`,`article_id`)
				VALUES('comment' ,'no', :Id, :comment_id, :article_id);");
				$maRequete->execute([
					":Id" => $user_id,
					":comment_id" => $comment_id['comment_id'],
					":article_id" => $article_id
				]);

            http_response_code(302);
            $direction = explode("/",$_SERVER["HTTP_REFERER"]);
            if($direction[3] === "profile") {
                header("Location: /profile");
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