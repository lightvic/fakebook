<?php

// start buffering
ob_start();
$title = "Fakebook - Fakenger";

$user_id = $_SESSION["user"]["user_id"];
$chat_id = $_SESSION["chat"]["chat_id"];
$members = $_SESSION["chat_members"];

require_once __DIR__ . "/../../html_partial/messenger/chat.php";
// clean buffering in $content
$content = ob_get_clean();

?>