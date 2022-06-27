<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modify_status"])) {
        require "../database/pdo.php";
        $group_id = $_SESSION["group"]["group_id"];
        $new_status = filter_input(INPUT_POST, "modify_status");
        $maRequete = $pdo->prepare("UPDATE `groups` SET `status` = :new_status WHERE `group_id` = :groupId");
        $maRequete->execute([
            ":new_status" => $new_status,
            ":groupId" => $group_id
        ]);
        $_SESSION["group"]["status"] = $new_status;

		// if the group becomes public, all pending requests are immediately approved
		if ($new_status === "public"){
			$maRequete = $pdo->prepare("UPDATE `members` SET `status` = 'approved' WHERE `group_id` = :groupId");
			$maRequete->execute([
				":groupId" => $group_id
        ]);
		}

        http_response_code(302);
        header('Location: /settings_group');
        exit();
    }
}
