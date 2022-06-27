<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        require_once "../database/pdo.php";
        // get information from fetch method in $_POST
        $content = $_POST["new_message"];
        $chat_id = $_POST["chat_id"];
        $user_id = $_SESSION["user"]["user_id"];

        // insert new message in database
        $maRequete = $pdo->prepare("INSERT INTO `messages` (`content`, `chat_id`, `user_id`) VALUES(:content, :chatId, :userId)");
        $maRequete->execute([
            ":content" => $content,
            ":chatId" => $chat_id,
            ":userId" => $user_id
        ]);
        exit();
    }
?>