<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $chat_id = filter_input(INPUT_POST, "quit_chat_id");
        $user_id = $_SESSION["user"]["user_id"];
        require_once __DIR__ . "/../../database/pdo.php";

        // count members of chat selected
        $maRequete = $pdo->prepare("SELECT count(*) FROM `chat_members` WHERE `chat_id` = :chatId;");
        $maRequete->execute([
            ":chatId" => $chat_id
        ]);
        $chat = $maRequete->fetch();
        // if only two members, delete chat
        if ($chat["count(*)"] === "2") {
            $maRequete = $pdo->prepare("DELETE FROM `chats` WHERE `chat_id` = :chatId");
            $maRequete->execute([
                ":chatId" => $chat_id
            ]);
        } else { // if more than two, delete user from member of chat selected
            $maRequete = $pdo->prepare("DELETE FROM `chat_members` WHERE `chat_id` = :chatId AND `user_id` = :userId");
            $maRequete->execute([
                ":chatId" => $chat_id,
                ":userId" => $user_id
            ]);
        }
        http_response_code(302);
        header('Location: /conversation');
        exit();
    }
?>