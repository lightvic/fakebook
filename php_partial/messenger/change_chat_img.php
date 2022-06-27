<?php
    // if Post method
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        require_once "../database/pdo.php";
        $chat_id = $_SESSION["chat"]["chat_id"];
        // path to load img
        $target_dir = __DIR__ . "/../../public/img_chat_profil/";
        $full_name = $_FILES["fileToUpload"]["name"];
        $name_exploded = explode(".",$full_name);
        require __DIR__ . "/../function/uuid.php";
        $file_name = guidv4($full_name);
        $extension = $name_exploded[1];
        $full_name = $file_name . "." . $extension;
        $target_file = $target_dir . basename($full_name);
        $uploadOk = 1;
        // lower the extension (png, jpg ect)
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(isset($_POST["fileToUpload"])) {
            // si c'est une image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
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


        // si l'image est inferieur à 
        if ($_FILES["fileToUpload"]["size"] > 5971520) {
            $message = "image trop volumineuse";
            http_response_code(403);
            require_once __DIR__ . "/../../html_partial/alert/banniere.php";
            $uploadOk = 0;
        }


        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
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
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // if upload succeeded
                // add result in database and session
                $maRequete = $pdo->prepare(
                    "UPDATE `chats` SET `chat_pic` = :chat_pic WHERE `chat_id` = :chatId"
                );
                $maRequete->execute([
                    ":chat_pic" => $full_name,
                    ":chatId" => $chat_id
                ]);
                $_SESSION['chat']['chat_pic'] = $full_name;
                http_response_code(302);
                // go back to chat
                header("Location: /chat");
                exit();
            } else {
                $message = "Une erreur est survenue";
                http_response_code(403);
                require_once __DIR__ . "/../../html_partial/alert/banniere.php";
            }
        }
    }
?>