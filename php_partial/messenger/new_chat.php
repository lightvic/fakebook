<?php

// start buffering
ob_start();

$title = "Fakebook - New chat";
require_once __DIR__ . "/../../database/pdo.php";
$user_id = $_SESSION["user"]["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // get every friend_id of the user
    $maRequete = $pdo->prepare("SELECT user_id_a, user_id_b FROM `relationships` WHERE (`user_id_a` = :userId OR `user_id_b` = :userId) AND (`status` = 'approved') AND (`blocked` = 'no' ) ");
    $maRequete->execute([
        ":userId" => $user_id
    ]);
    $friend_ids = $maRequete->fetchAll();
    $friend_profils = [];
    // get every information of each friend of the user
    foreach ($friend_ids as $friend_id) {
        if ($friend_id["user_id_a"] !== $user_id) {
            $maRequete = $pdo->prepare("SELECT `first_name`, `last_name`, `profil_picture`, `user_id` FROM `users` WHERE `user_id` = :userId ");
            $maRequete->execute([
                ":userId" => $friend_id["user_id_a"]
            ]);
            $get_profil = $maRequete->fetch();
            array_push($friend_profils, $get_profil);
        } else {
            $maRequete = $pdo->prepare("SELECT `first_name`, `last_name`, `profil_picture`, `user_id` FROM `users` WHERE `user_id` = :userId");
            $maRequete->execute([
                ":userId" => $friend_id["user_id_b"]
            ]);
            $get_profil = $maRequete->fetch();
            array_push($friend_profils, $get_profil);
        }
    }    
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!isset($_POST["friend0"])) {
        header("Location: /new_chat");
        exit;
    }
    $name = $_POST["name"];
    require __DIR__ . "/../function/uuid.php";
    $uuid = guidv4($name);

    // insert new chat in database
    $maRequete = $pdo->prepare("INSERT INTO `chats` (`name`, `uuid`) VALUES(:name, :uuid)");
    $maRequete->execute([
        ":name" => $name,
        ":uuid" => $uuid
    ]);

    // get chat_id of the new chat
    $maRequete = $pdo->prepare("SELECT `chat_id` FROM `chats` WHERE `uuid` = :uuid ");
    $maRequete->execute([
        ":uuid" => $uuid
    ]);
    $chat_id = $maRequete->fetch();
    $chat_id = $chat_id["chat_id"];
    
    // insert user in new chat with chat_id
    $maRequete = $pdo->prepare("INSERT INTO `chat_members` (`chat_id`, `user_id`) VALUES(:chatId, :userId)");
    $maRequete->execute([
        ":chatId" => $chat_id,
        ":userId" => $user_id
    ]);

    // insert every friends wanted in new chat with chat_id
    foreach ($_POST as $key => $value) {
        if ($_POST["name"] !== $value) {
            $maRequete = $pdo->prepare("INSERT INTO `chat_members` (`chat_id`, `user_id`) VALUES(:chatId, :userId)");
            $maRequete->execute([
                ":chatId" => $chat_id,
                ":userId" => $value
            ]);
        }
    }
    
    // get all informations from new_chat created
    $maRequete = $pdo->prepare("SELECT * FROM `chats` WHERE `chat_id` = :chatId;");
    $maRequete->execute([
        ":chatId" => $chat_id
    ]);
    $chat = $maRequete->fetch();

    // get every chat members of new chat created
    $maRequete = $pdo->prepare("SELECT * FROM `chat_members` WHERE `chat_id` = :chatId;");
    $maRequete->execute([
        ":chatId" => $chat_id
    ]);
    $chat_members = $maRequete->fetch();

    // set chat information and members in session
    $_SESSION["chat"] = $chat;
    $_SESSION["chat_members"] = $chat_members;
    http_response_code(302);
    // go to chat
    header('Location: /chat');
    exit();
}

require_once __DIR__ . '/../../html_partial/messenger/new_chat.php';
// clean buffering in $content
$content = ob_get_clean();