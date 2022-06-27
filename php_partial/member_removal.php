<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["member_removal"])) {
            // get info from form
            require "../database/pdo.php";
            $user_id = filter_input(INPUT_POST, "member_removal_account");
            $group_id = filter_input(INPUT_POST, "member_removal_group");

			// delete ship from database
			$maRequete = $pdo->prepare("DELETE FROM `members` WHERE `group_id` = :groupId AND `user_id` = :userId;");
			$maRequete->execute([
				":userId" => $user_id,
				":groupId" => $group_id
			]);

			$maRequete = $pdo->prepare("DELETE FROM `articles` WHERE `group_id` = :groupId AND `user_id` = :userId;");
			$maRequete->execute([
				":userId" => $user_id,
				":groupId" => $group_id
			]);

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