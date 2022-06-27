<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);
    if (isset($_POST["modifi_lname"])) {
        require "../database/pdo.php";
        $user_id = $_SESSION["user"]["user_id"];
        $new_last_name = filter_input(INPUT_POST, "modifi_lname");
        $maRequete = $pdo->prepare("UPDATE `users` SET `last_name` = :last_name WHERE `user_id` = :userId");
        $maRequete->execute([
            ":last_name" => $new_last_name,
            ":userId" => $user_id
        ]);
        $_SESSION["user"]["last_name"] = $new_last_name;
        http_response_code(302);
        header('Location: /settings_profil');
        exit();
    }
}
