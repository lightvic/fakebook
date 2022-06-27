<?php
ob_start();
require_once "../database/pdo.php";
$user_id = $_SESSION["user"]["user_id"];

$maRequete = $pdo->prepare('SELECT `user_id` ,`first_name`, `last_name` , `profil_picture` FROM `users`;');
    $maRequete->execute();
    $profiles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// check where you are a request friend on pending
$maRequete = $pdo->prepare('SELECT * FROM `relationships` WHERE (`user_id_b` = :user) AND (`status` = "pending") ;');
    $maRequete->execute([
        ":user" => $user_id
    ]);
    $friends = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// select all the notifications
$maRequete = $pdo->prepare('SELECT * FROM `notifications` WHERE `user_id` = :user;');
    $maRequete->execute([
        ":user" => $user_id
    ]);
    $notifications = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// put notifications on yes 
$maRequete = $pdo->prepare('UPDATE `notifications` SET `seen` = "yes" WHERE `user_id` = :Id;');
    $maRequete->execute([
        ":Id" => $user_id
    ]);

$maRequete = $pdo->prepare('SELECT `article_id` FROM `articles` WHERE `user_id` = :user;');
    $maRequete->execute([
        ":user" => $user_id
    ]);
    $articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);


require __DIR__ . "/../html_partial/notifications.php";



$content = ob_get_clean(); //je stock le tampon dans cette variable
?>