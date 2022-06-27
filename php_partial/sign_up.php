<?php
// start buffering
ob_start();
$title = "sign up";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prenom = filter_input(INPUT_POST, "firstName");
    $nom = filter_input(INPUT_POST, "lastName");
    $mail = filter_input(INPUT_POST, "email");
    $mdp = hash("sha512", filter_input(INPUT_POST, "password"));
    $confirmMdp = hash("sha512", filter_input(INPUT_POST, "confirmPassword"));
    require_once __DIR__ . "/../database/pdo.php";
    
    // watch in database if user exist already
    $maRequete = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email;");
    $maRequete->execute([
        ":email" => $mail
    ]);
    $user = $maRequete->fetch();

    // if no result, create new user
    if ($user == false && strcmp($mdp, $confirmMdp) == 0) {
        $maRequete = $pdo->prepare("INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`) VALUES(:first_name, :last_name, :email, :mdp)");
        $maRequete->execute([
            ":first_name" => $prenom,
            ":last_name" => $nom,
            ":email" => $mail,
            ":mdp" => $mdp
        ]);
        
        $maRequete = $pdo->prepare("SELECT `user_id`, `email`, `password`, `first_name`, `last_name`, `profil_picture`, `banner`, `status`, `theme` FROM `users` WHERE `email` = :email;");
        $maRequete->execute([
            ":email" => $mail
        ]);

        $user = $maRequete->fetch();
        // set session witch new user infos
        $_SESSION["user"] = $user;

		// creating new user's stats
		$maRequete = $pdo->prepare("INSERT INTO `stats` (`user_id`) VALUES (:userId);");
		$maRequete->execute([
			":userId" => $_SESSION["user"]["user_id"]
		]);
        // got to timeline
        http_response_code(302);
        header('Location: /timeline');
        exit();
        // if user already exists, send error message
    } elseif ($user == true){
        $message = "L'utilisateur existe déjà";
        http_response_code(403);
        require_once __DIR__ . "/../html_partial/alert/banniere.php";
    } else {
        $message = "Les mots de passe ne correspondent pas";
        http_response_code(403);
        require_once __DIR__ . "/../html_partial/alert/banniere.php";
    }
}

require_once __DIR__ . '/../html_partial/sign_up.php';
// clean buffering in $content
$content = ob_get_clean();

