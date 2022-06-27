<?php
$user_id = $_SESSION["user"]["user_id"];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["invite_accepted"])) {
        require_once __DIR__ . "/../database/pdo.php";
        $group_id = filter_input(INPUT_POST, "invite_group");
		$friend_id =  filter_input(INPUT_POST, "invite_friend");

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
			"UPDATE `members` SET `status` = :st WHERE `user_id`= :userId AND `group_id`= :groupId");
			$maRequete->execute([
				":userId" => $friend_id,
				":groupId" => $group_id,
				":st" => $status
			]);

		// just making sure the group session is set before going back
		$maRequete = $pdo->prepare(
			"SELECT * FROM `groups` WHERE `group_id` = :groupId;");
			$maRequete->execute([
				":groupId" => $group_id
			]);
		$current_group = $maRequete->fetch();
		$_SESSION["group"] = $current_group;

		http_response_code(302);
		header("Location: /group");
		exit();
    }
}
?>