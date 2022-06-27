<?php
if($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../database/pdo.php";  // accessing the database
	$choice = $_POST["choice"];
    $user_id = $_SESSION["user"]["user_id"];
    if ($choice == 0) {
        $theme = 1;
    } else {
        $theme = 0;
    }
    $maRequete = $pdo->prepare("UPDATE `users` SET `theme`= :theme WHERE `user_id` = :userId");
        $maRequete->execute([
            ":theme" => $theme,
            ":userId" => $user_id
        ]);
    $_SESSION["user"]["theme"] = $theme;
    http_response_code(302);
    header('Location: /settings_profil');
    exit();
}
?>