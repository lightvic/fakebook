<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["modify_description"])) {
        require "../database/pdo.php";
        $page_id = $_SESSION["page"]["page_id"];
        $new_description = filter_input(INPUT_POST, "modify_description");
        $maRequete = $pdo->prepare("UPDATE `pages` SET `description` = :new_description WHERE `page_id` = :pageId");
        $maRequete->execute([
            ":new_description" => $new_description,
            ":pageId" => $page_id
        ]);
        $_SESSION["page"]["description"] = $new_description;
        http_response_code(302);
        header('Location: /settings_public_page');
        exit();
    }
}
