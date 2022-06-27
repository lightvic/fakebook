<?php
$user_id = $_SESSION["user"]["user_id"];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["member_request"])) {
        require_once __DIR__ . "/../database/pdo.php";
        $group_id = $_SESSION["group"]["group_id"];

		// getting the group's status (private/public)
		$maRequete = $pdo->prepare(
			"SELECT `status` FROM `groups` WHERE `group_id`=:groupId");
			$maRequete->execute([
				":groupId" => $group_id
			]);
		$privacy = $maRequete->fetch();
	
		if($privacy[0] === 'public') {
			$status = 'approved';
		} else {
			$status = 'pending';
		};
		
		$maRequete = $pdo->prepare(
			"INSERT INTO `members` (`user_id`, `group_id`, `status`)
			VALUES(:userId, :groupId, :st)");
			$maRequete->execute([
				":userId" => $user_id,
				":groupId" => $group_id,
				":st" => $status
			]);

		http_response_code(302);
		header("Location: /group");
		exit();
    }
}
?>