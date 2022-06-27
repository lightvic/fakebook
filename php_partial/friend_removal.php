<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["friend_removal"])) {
            // get info from form
            require "../database/pdo.php";
            $profile_id = filter_input(INPUT_POST, "friend_removal");
            $user_id = $_SESSION["user"]["user_id"];
			
			
			// check if the relatioship was approved to update table stats (because nb_friends only goes up once the relatioship is approved)
			// It doesn't work yet but at least it doesn't make the page crash
			$maRequete = $pdo->prepare("SELECT `user_id_a`, `user_id_b`, `status` FROM `relationships` WHERE (`user_id_a` = :profile_id AND `user_id_b` = :userId) OR (`user_id_b` = :profile_id AND `user_id_a` = :userId);");
			$maRequete->execute([
				":profile_id" => $profile_id,
				":userId" => $user_id
			]);
			$relationship = $maRequete->fetch();
			if ($relationship['status'] == 'approved') {
				$maRequete = $pdo->prepare(
					"UPDATE `stats`
					SET `nb_friends` = `nb_friends` - 1
					WHERE `user_id` = :profile_id OR `user_id` = :userId;");
					$maRequete->execute([
						":profile_id" => $profile_id,
						":userId" => $user_id
					]);
			}


			// delete relatioship from database
			$maRequete = $pdo->prepare("DELETE FROM `relationships` WHERE (`user_id_a` = :profile_id AND `user_id_b` = :userId) OR (`user_id_b` = :profile_id AND `user_id_a` = :userId);");
			$maRequete->execute([
				":profile_id" => $profile_id,
				":userId" => $user_id
			]);
			// go back to profile
			http_response_code(302);

			header("Location: /profile");
			exit();
		}
    }
?>