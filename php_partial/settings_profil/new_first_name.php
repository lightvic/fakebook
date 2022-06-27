<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modifi_fname"])) {
        require "../database/pdo.php";
        $user_id = $_SESSION["user"]["user_id"];
        $new_first_name = filter_input(INPUT_POST, "modifi_fname");
        $maRequete = $pdo->prepare("UPDATE `users` SET `first_name` = :first_name WHERE `user_id` = :userId");
        $maRequete->execute([
            ":first_name" => $new_first_name,
            ":userId" => $user_id
        ]);
        $_SESSION["user"]["first_name"] = $new_first_name;
        http_response_code(302);
        header('Location: /settings_profil');
        exit();
    }
}
