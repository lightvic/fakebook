<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modify_name"])) {
        require "../database/pdo.php";
        $page_id = $_SESSION["page"]["page_id"];
        $new_name = filter_input(INPUT_POST, "modify_name");
        $maRequete = $pdo->prepare("UPDATE `pages` SET `name` = :new_name WHERE `page_id` = :pageId");
        $maRequete->execute([
            ":new_name" => $new_name,
            ":pageId" => $page_id
        ]);
        $_SESSION["page"]["name"] = $new_name;
        http_response_code(302);
        header('Location: /settings_public_page');
        exit();
    }
}
