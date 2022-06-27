<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["remove_admin"])) {
            // get info from form
            require "../database/pdo.php";
            $group_id = $_SESSION["group"]["group_id"];
            $user_id = $_SESSION["user"]["user_id"];
			// delete relatioship from database
			$maRequete = $pdo->prepare("DELETE FROM `admins` WHERE `group_id` = :groupId AND `user_id` = :userId;");
			$maRequete->execute([
				":groupId" => $group_id,
				":userId" => $user_id
			]);
			$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `group_id` = :groupId;");
			$maRequete->execute([
				":groupId" => $group_id
			]);
			$admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			if (count($admins) === 0){
				$maRequete = $pdo->prepare("DELETE FROM `groups` WHERE `group_id` = :groupId;");
				$maRequete->execute([
					":groupId" => $group_id,
				]);
				http_response_code(302);
				header("Location: /timeline");
				exit();
			}
			// go back to public_group
			http_response_code(302);

			header("Location: /group"); 
			exit();
		}
    }
?>