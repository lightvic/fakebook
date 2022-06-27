<?php
$user_id = $_SESSION["user"]["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old_password =  hash("sha512", filter_input(INPUT_POST, "password"));
    $new_password = hash("sha512", filter_input(INPUT_POST, "new_password"));
    $confirm_password = hash("sha512", filter_input(INPUT_POST, "confirm_password"));
    require_once __DIR__ . "/../../database/pdo.php";
    $maRequete = $pdo->prepare("SELECT `password` FROM `users` WHERE `password` = :pass AND `user_id` = :userId;");
    $maRequete->execute([
        ":pass" => $old_password,
        ":userId" => $user_id
    ]);

    $password = $maRequete->fetch();

    if ($password == true && strcmp($new_password, $confirm_password) == 0) {
        $maRequete = $pdo->prepare("UPDATE `users` SET `password` = :new_password WHERE `user_id` = :userId");
        $maRequete->execute([
            ":new_password" => $new_password,
            ":userId" => $user_id
        ]);
        $_SESSION["user"]["password"] = $new_password;
        http_response_code(302);
        header("Location: /settings_profil");
        exit();
    } elseif ($password == false) {
        $message = "Le mot de passe est incorrecte";
        http_response_code(403);
        require_once __DIR__ . "/../../html_partial/alert/banniere.php";
    } else {
        $message = "Les mots de passe ne correspondent pas";
        http_response_code(403);
        require_once __DIR__ . "/../html_partial/alert/banniere.php";
    }
}
