<?php
require_once "../database/pdo.php";
$user_id = $_SESSION["user"]["user_id"];
$chat_id = $_SESSION["chat"]["chat_id"];
$members = $_SESSION["chat_members"];

// get every message from the database for the chat wanted
$maRequete = $pdo->prepare("SELECT * FROM `messages` WHERE `chat_id` = :chatId ORDER BY `date`");
$maRequete->execute([
    ":chatId" => $chat_id
]);
$messages = $maRequete->fetchAll(PDO::FETCH_ASSOC);
// transorm result in json for fetch method
echo json_encode([
    "members" => $_SESSION["chat_members"],
    "user" => $_SESSION["user"],
    "messages" => $messages
]);
exit();