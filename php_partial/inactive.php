<?php
ob_start();
if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    require_once __DIR__ . "/../database/pdo.php"; //je récupère le PDO
    $user_id = $_SESSION["user"]["user_id"];

    if($_POST['enable']) {
        $maRequete=$pdo->prepare("UPDATE `users` SET status = 'active' WHERE `user_id` = $user_id;");
        $maRequete->execute();
        header("Location: /timeline");
    }
    elseif($_POST['delete']){
        $maRequete=$pdo->prepare("DELETE FROM `users` WHERE `user_id` = $user_id;");
        $maRequete->execute();
        header("Location: /login");
    }
}

//j'appelle l'html de cette page
require_once __DIR__ . "/../html_partial/inactive.php";
$content = ob_get_clean(); //je stock le tampon dans cette variable
?>