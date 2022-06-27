<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modifi_email"])) {
        require "../database/pdo.php";
        $user_id = $_SESSION["user"]["user_id"];
        $email = filter_input(INPUT_POST, "modifi_email");
        $maRequete = $pdo->prepare("UPDATE `users` SET `email` = :email WHERE `user_id` = :userId");
        $maRequete->execute([
            ":email" => $email,
            ":userId" => $user_id
        ]);
        $_SESSION["user"]["email"] = $email;
        http_response_code(302);
        header('Location: /settings_profil');
        exit();
    }
}
