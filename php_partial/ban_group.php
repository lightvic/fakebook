<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["ban"])) {
            // get info from form
            require "../database/pdo.php";
            $group_id = filter_input(INPUT_POST, "ban_group");
            $profile_id = filter_input(INPUT_POST, "ban_account");
			// updating database
			$maRequete = $pdo->prepare("UPDATE `members` SET `banned`='yes' WHERE `group_id`=:groupId AND `user_id`=:profileId;");
			$maRequete->execute([
				":groupId" => $group_id,
				":profileId" => $profile_id
			]);
			$maRequete = $pdo->prepare("DELETE FROM `articles` WHERE `group_id` = :groupId AND `user_id` = :profileId;");
			$maRequete->execute([
				":profileId" => $profile_id,
				":groupId" => $group_id
			]);

			// go back to group
			http_response_code(302);

			header("Location: /group");
			exit();
		}
    }
?>