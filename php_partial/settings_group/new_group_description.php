<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modify_description"])) {
        require "../database/pdo.php";
        $group_id = $_SESSION["group"]["group_id"];
        $new_description = filter_input(INPUT_POST, "modify_description");
        $maRequete = $pdo->prepare("UPDATE `groups` SET `description` = :new_description WHERE `group_id` = :groupId");
        $maRequete->execute([
            ":new_description" => $new_description,
            ":groupId" => $group_id
        ]);
        $_SESSION["group"]["description"] = $new_description;
        http_response_code(302);
        header('Location: /settings_group');
        exit();
    }
}
