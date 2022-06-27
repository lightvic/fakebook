<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["ban"])) {
            // get info from form
            require "../database/pdo.php";
            $page_id = filter_input(INPUT_POST, "ban_page");
            $profile_id = filter_input(INPUT_POST, "ban_account");
			// updating database
			$maRequete = $pdo->prepare("UPDATE `followers` SET `banned`='yes' WHERE `page_id`=:pageId AND `user_id`=:profileId;");
			$maRequete->execute([
				":pageId" => $page_id,
				":profileId" => $profile_id
			]);

			// go back to public_page
			http_response_code(302);

			header("Location: /public_page");
			exit();
		}
    }
?>