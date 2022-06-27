<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["new_admin"])) {
            // get info from form
            require "../database/pdo.php";
            $group_id = filter_input(INPUT_POST, "new_admin_group");
            $profile_id = filter_input(INPUT_POST, "new_admin_account");
			var_dump($group_id);
			var_dump($profile_id);

			// delete relatioship from database
			$maRequete = $pdo->prepare("INSERT INTO `admins` (`group_id`,`user_id`) VALUES (:groupId, :profileId);");
			$maRequete->execute([
				":groupId" => $group_id,
				":profileId" => $profile_id
			]);

			// go back to public_group
			http_response_code(302);

			header("Location: /group");
			exit();
		}
    }
?>