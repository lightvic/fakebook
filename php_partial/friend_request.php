<?php
$user_id = $_SESSION["user"]["user_id"];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["friend_request"])) {
        require_once __DIR__ . "/../database/pdo.php";
        $profile_id = filter_input(INPUT_POST, 'friend_request');
		$maRequete = $pdo->prepare(
			"INSERT INTO `relationships` (`user_id_a`, `user_id_b`)
			VALUES(:userId, :profileId)");
			$maRequete->execute([
				":userId" => $user_id,
				":profileId" => $profile_id
			]);

		// pbm : it seems we're switching to method "get" here so we end up on our own profile page instead of our friend's.
		http_response_code(302);
		// $direction = explode("/",$_SERVER["HTTP_REFERER"]);
		header("Location: /profile");
		exit();
    }
}
?>