<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["unban"])) {
            // get info from form
            require "../database/pdo.php";
            $group_id = filter_input(INPUT_POST, "unban_group");
            $profile_id = filter_input(INPUT_POST, "unban_account");
			var_dump($group_id);
			var_dump($profile_id);

			// updating database
			// $maRequete = $pdo->prepare("UPDATE `members` SET `banned`='no' WHERE `group_id`=:groupId AND `user_id`=:profileId;");
			// $maRequete->execute([
			// 	":groupId" => $group_id,
			// 	":profileId" => $profile_id
			// ]);
			$maRequete = $pdo->prepare("DELETE FROM `members` WHERE `group_id`=:groupId AND `user_id`=:profileId;");
			$maRequete->execute([
				":groupId" => $group_id,
				":profileId" => $profile_id
			]);

			// go back to group
			http_response_code(302);

			header("Location: /group");
			exit();
		}
    }
?>