<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["pageInput"])) {
            require_once __DIR__ . "/../database/pdo.php";
            $page_name = filter_input(INPUT_POST, "pageInput");
            $page_description = filter_input(INPUT_POST, "descriptionInput");
            $user_id = $_SESSION["user"]["user_id"];
            // updating table pages
            $maRequete = $pdo->prepare(
                "INSERT INTO `pages` (`name`, `description`)
                VALUES(:page_name, :page_description);");
                $maRequete->execute([
                    ":page_name" => $page_name,
                    ":page_description" => $page_description
                ]);
			// get the new page's id
			$maRequete = $pdo->prepare(
                "SELECT `page_id`, `creation_date` FROM `pages` WHERE `name` = :page_name ORDER BY `creation_date` DESC;");
                $maRequete->execute([
                    ":page_name" => $page_name
                ]);
			$pages = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			$page_id = $pages[0]["page_id"];

			$maRequete = $pdo->prepare(
                "SELECT * FROM `pages` WHERE `page_id` = :pageId;");
                $maRequete->execute([
                    ":pageId" => $page_id
                ]);
			$current_page = $maRequete->fetch();
			

			$_SESSION["page"] = $current_page;


			// updating table admins
			$maRequete = $pdo->prepare(
                "INSERT INTO `admins` (`page_id`, `user_id`)
                VALUES(:pageId, :userId);");
                $maRequete->execute([
                    ":pageId" => $page_id,
                    ":userId" => $user_id
                ]);
			// updating table admins
			$maRequete = $pdo->prepare(
                "INSERT INTO `followers` (`page_id`, `user_id`)
                VALUES(:pageId, :userId);");
                $maRequete->execute([
                    ":pageId" => $page_id,
                    ":userId" => $user_id
                ]);
			
			$maRequete = $pdo->prepare(
				"SELECT * FROM `admins` WHERE `page_id` = :pageId;");
				$maRequete->execute([
					":pageId" => $page_id
				]);
			$current_admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			
			$_SESSION["page_admin"] = $current_admins;

            http_response_code(302);
            header("Location: /public_page"); // voir avec adrien pr envoyer le page_id
            
            exit();
        }
    }
?>