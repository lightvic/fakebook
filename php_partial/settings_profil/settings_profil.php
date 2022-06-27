<?php
ob_start();

require_once __DIR__ . "/../../database/pdo.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $profile_id = $_SESSION["user"]["user_id"];
    $email = $_SESSION["user"]["email"];
    $first_name = $_SESSION["user"]["first_name"];
    $last_name = $_SESSION["user"]["last_name"];
    $password = $_SESSION["user"]["password"];
    $profil_picture = $_SESSION["user"]["profil_picture"];
    $banner = $_SESSION["user"]["banner"];

    $user_id = $_SESSION["user"]["user_id"];
    $maRequete = $pdo->prepare("SELECT `theme` FROM `users` WHERE `user_id` = :userId;");
        $maRequete->execute([
            ":userId" => $user_id
        ]);
	$theme = $_SESSION["user"]["theme"];
}


require_once __DIR__ . "/../../html_partial/settings_profil/settings_profil.php";
$content = ob_get_clean();
