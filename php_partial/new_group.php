<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["groupInput"])) {
            require_once __DIR__ . "/../database/pdo.php";
            $group_name = filter_input(INPUT_POST, "groupInput");
            $group_description = filter_input(INPUT_POST, "descriptionInput");
            $group_privacy = filter_input(INPUT_POST, "privacyInput");
            $user_id = $_SESSION["user"]["user_id"];
            // updating table groups
            $maRequete = $pdo->prepare(
                "INSERT INTO `groups` (`name`, `description`, `status`)
                VALUES(:group_name, :group_description, :group_privacy);");
                $maRequete->execute([
                    ":group_name" => $group_name,
                    ":group_description" => $group_description,
                    ":group_privacy" => $group_privacy
                ]);
			// get the new group's id
			$maRequete = $pdo->prepare(
                "SELECT `group_id`, `creation_date` FROM `groups` WHERE `name` = :group_name ORDER BY `creation_date` DESC;");
                $maRequete->execute([
                    ":group_name" => $group_name
                ]);
			$groups = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			$group_id = $groups[0]["group_id"];

			$maRequete = $pdo->prepare(
                "SELECT * FROM `groups` WHERE `group_id` = :groupId;");
                $maRequete->execute([
                    ":groupId" => $group_id
                ]);
			$current_group = $maRequete->fetch();
			

			$_SESSION["group"] = $current_group;


			// updating table admins
			$maRequete = $pdo->prepare(
                "INSERT INTO `admins` (`group_id`, `user_id`)
                VALUES(:groupId, :userId);");
                $maRequete->execute([
                    ":groupId" => $group_id,
                    ":userId" => $user_id
                ]);
			// updating table admins
			$maRequete = $pdo->prepare(
                "INSERT INTO `members` (`group_id`, `user_id`, `status`)
                VALUES(:groupId, :userId, 'approved');");
                $maRequete->execute([
                    ":groupId" => $group_id,
                    ":userId" => $user_id
                ]);
			
			$maRequete = $pdo->prepare(
				"SELECT * FROM `admins` WHERE `group_id` = :groupId;");
				$maRequete->execute([
					":groupId" => $group_id
				]);
			$current_admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			
			$_SESSION["group_admin"] = $current_admins;

            http_response_code(302);
            header("Location: /group"); // voir avec adrien pr envoyer le group_id
            
            exit();
        }
    }
?>