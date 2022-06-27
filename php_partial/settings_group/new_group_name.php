<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modify_name"])) {
        require "../database/pdo.php";
        $group_id = $_SESSION["group"]["group_id"];
        $new_name = filter_input(INPUT_POST, "modify_name");
        $maRequete = $pdo->prepare("UPDATE `groups` SET `name` = :new_name WHERE `group_id` = :groupId");
        $maRequete->execute([
            ":new_name" => $new_name,
            ":groupId" => $group_id
        ]);
        $_SESSION["group"]["name"] = $new_name;
        http_response_code(302);
        header('Location: /settings_group');
        exit();
    }
}
