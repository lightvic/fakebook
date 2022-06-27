<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../../database/pdo.php";
    $group_id = $_SESSION['group']['group_id'];
    $target_dir = __DIR__ . "/../../public/img_baniere/";
    $full_name = $_FILES["upload_ban"]["name"];
    $name_exploded = explode(".", $full_name);
    require __DIR__ . "/../function/uuid.php";
    $file_name = guidv4($full_name);
    $extension = $name_exploded[1];
    $full_name = $file_name . "." . $extension;
    $target_file = $target_dir . basename($full_name);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (isset($_POST["upload_ban"])) {
        $check = getimagesize($_FILES["upload_ban"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $message = "ce n'est pas une image";
            http_response_code(403);
            require_once __DIR__ . "/../../html_partial/alert/banniere.php";
            $uploadOk = 0;
        }
    }

    if (file_exists($target_file)) {
        $message = "l'image existe déjà";
        http_response_code(403);
        require_once __DIR__ . "/../../html_partial/alert/banniere.php";
        $uploadOk = 0;
    }


    if ($_FILES["upload_ban"]["size"] > 5971520) {
        $message = "image trop volumineuse";
        http_response_code(403);
        require_once __DIR__ . "/../../html_partial/alert/banniere.php";
        $uploadOk = 0;
    }


    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "seulement jpg, png, jpeg et gif";
        http_response_code(403);
        require_once __DIR__ . "/../../html_partial/alert/banniere.php";
        $uploadOk = 0;
    }

    if ($uploadOk === 0) {
        $message = "Une erreur est survenue";
        http_response_code(403);
        require_once __DIR__ . "/../../html_partial/alert/banniere.php";
    } else {
        if (move_uploaded_file($_FILES["upload_ban"]["tmp_name"], $target_file)) {
            $maRequete = $pdo->prepare(
                "UPDATE `groups` SET `banner` = :banner WHERE `group_id` = :groupId"
            );
            $maRequete->execute([
                ":banner" => $full_name,
                ":groupId" => $group_id
            ]);
            $_SESSION['group']['banner'] = $full_name;
            http_response_code(302);
            header("Location: /settings_group");
            exit();
        } else {
            $message = "Une erreur est survenue";
            http_response_code(403);
            require_once __DIR__ . "/../../html_partial/alert/banniere.php";
        }
    }
}
