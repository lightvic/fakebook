<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["unfollow"])) {
            // get info from form
            require "../database/pdo.php";
            $page_id = $_SESSION["page"]["page_id"];
            $user_id = $_SESSION["user"]["user_id"];
			// delete relatioship from database
			$maRequete = $pdo->prepare("DELETE FROM `followers` WHERE `page_id` = :pageId AND `user_id` = :userId;");
			$maRequete->execute([
				":pageId" => $page_id,
				":userId" => $user_id
			]);

			// go back to public_page
			http_response_code(302);

			header("Location: /public_page"); 
			exit();
		}
    }
?>