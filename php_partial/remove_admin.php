<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["remove_admin"])) {
            // get info from form
            require "../database/pdo.php";
            $page_id = $_SESSION["page"]["page_id"];
            $user_id = $_SESSION["user"]["user_id"];
			// delete relatioship from database
			$maRequete = $pdo->prepare("DELETE FROM `admins` WHERE `page_id` = :pageId AND `user_id` = :userId;");
			$maRequete->execute([
				":pageId" => $page_id,
				":userId" => $user_id
			]);
			$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `page_id` = :pageId;");
			$maRequete->execute([
				":pageId" => $page_id
			]);
			$admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
			if (count($admins) === 0){
				$maRequete = $pdo->prepare("DELETE FROM `pages` WHERE `page_id` = :pageId;");
				$maRequete->execute([
					":pageId" => $page_id,
				]);
				http_response_code(302);
				header("Location: /timeline");
				exit();
			}
			// we need to make sure the page session is set before going back
			$maRequete = $pdo->prepare(
				"SELECT * FROM `pages` WHERE `page_id` = :pageId;");
				$maRequete->execute([
					":pageId" => $page_id
				]);
			$current_page = $maRequete->fetch();
			$_SESSION["page"] = $current_page;
									
			// go back to public_page
			http_response_code(302);

			header("Location: /public_page"); 
			exit();
		}
    }
?>