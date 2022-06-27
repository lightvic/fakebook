<?php
ob_start();

require_once __DIR__ . "/../../database/pdo.php";
$title = "Fakebook - Paramètres du groupe " . $_SESSION["group"]["name"];
$h1 = "Paramètres de " . $_SESSION["group"]["name"];
if ($_SERVER["REQUEST_METHOD"] === "GET") {
	$group_id = $_SESSION["group"]["group_id"];
    $name = $_SESSION["group"]["name"];
    $description = $_SESSION["group"]["description"];
    $picture = $_SESSION["group"]["picture"];
    $banner = $_SESSION["group"]["banner"];
    $status = $_SESSION["group"]["status"];


}


require_once __DIR__ . "/../../html_partial/settings_group/settings_group.php";
$content = ob_get_clean();
