<?php
// start buffering
ob_start();
$title = "login";


if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    require_once __DIR__ . "/../database/pdo.php";
    $mail = filter_input(INPUT_POST, "mail");
    $mdp = hash("sha512", filter_input(INPUT_POST, "mdp"));
    
    // get user info by checking mail
    $maRequete = $pdo->prepare("SELECT `user_id`, `email`, `password`, `first_name`, `last_name`, `profil_picture`, `banner`, `status`, `theme` FROM `users` WHERE `email` = :email;");
        $maRequete->execute([
            ":email" => $mail,
        ]);
    $user = $maRequete->fetch();
    // if no result, error message
    if (!$user || $user["password"] !== $mdp) {
        $message = "Utilisateur invalide";
        http_response_code(403);
        require_once __DIR__ . "/../html_partial/alert/banniere.php";
    } else { // if result, set user informations in session
        if ($user["status"]=="inactive"){
            $_SESSION["user"] = $user;
            http_response_code(302);
            header("Location: /inactive");
            exit();
        }
        else{
        $_SESSION["user"] = $user;
        http_response_code(302);
        // go to timeline
        header("Location: /timeline");
        exit();
        }
    }
}

require_once __DIR__ . "/../html_partial/login.php";
// clean buffering in $content
$content = ob_get_clean();
