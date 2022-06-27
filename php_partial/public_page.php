<?php
ob_start();

require_once __DIR__ . "/../database/pdo.php";  // accessing the database

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$page_id = filter_input(INPUT_POST, "page_id");
} else if (isset($_SESSION["page"])) {
	$page_id = $_SESSION["page"]["page_id"];
} else {
	$message = "Consultez une page avant de revenir ici";
	//indique que le serveur refuse d'autoriser la requête 
	http_response_code(403);
	//j'appelle ma bannière html pour afficher un message d'erreur
	require_once __DIR__ . "/../html_partial/alert/banniere.php";
	exit;
}
$user_id = $_SESSION["user"]["user_id"];
// updating $_SESSION["page"]


$maRequete = $pdo->prepare(
	"SELECT * FROM `pages` WHERE `page_id` = :pageId;");
	$maRequete->execute([
		":pageId" => $page_id
	]);
$current_page = $maRequete->fetch();
$_SESSION["page"] = $current_page;

$page_id = $_SESSION["page"]["page_id"];
$page = $_SESSION["page"];

// displaying the page's name and its past articles
$title = "Fakebook - Page " . $page["name"];
$h1 = $page["name"];
$maRequete = $pdo->prepare("SELECT * FROM `articles` WHERE `page_id` = :pageId ORDER BY `date` DESC");
$maRequete->execute([
	":pageId" => $page_id
]);
$articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// getting the page's stats
$nb_articles = Count($articles);

$maRequete = $pdo->prepare("SELECT `follower_id`, `user_id` FROM `followers` WHERE `page_id` = :pageId AND `banned` = 'no';");
	$maRequete->execute([
		":pageId" => $page_id
	]);
$followers = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$nb_followers = COUNT($followers);


$maRequete = $pdo->prepare("SELECT `user_id` FROM `followers` WHERE `page_id` = :pageId AND `user_id` = :userId;");
	$maRequete->execute([
		":pageId" => $page_id,
		":userId" => $user_id
	]);
$user_is_follower = $maRequete->fetchAll(PDO::FETCH_ASSOC);
if (COUNT($user_is_follower)>0){
	$is_follower = TRUE;
} else {
	$is_follower = FALSE;
}

$accounts = array();
foreach ($followers as $follower) {
	$maRequete = $pdo->prepare("SELECT `user_id`, `first_name`, `last_name`, `profil_picture` FROM `users` WHERE `user_id` = :Id;");
		$maRequete->execute([
			":Id" => $follower['user_id']
		]);
		$maRequete->setFetchMode(PDO::FETCH_ASSOC);
	array_push($accounts, $maRequete->fetch());
}


// getting the page's admins
$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `page_id` = :pageId;");
	$maRequete->execute([
		":pageId" => $page_id
	]);
$admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$nb_admins = Count($admins);

foreach ($admins as $admin) {
	if ($admin['user_id'] === $_SESSION["user"]["user_id"]) {
		$is_admin = TRUE;
		break;
	} else {
		$is_admin = FALSE;
	}
}


// getting those who were banned from the page (and checking whether the user himself is banned)

$maRequete = $pdo->prepare("SELECT `follower_id`, `user_id` FROM `followers` WHERE `page_id` = :pageId AND `banned` = 'yes';");
	$maRequete->execute([
		":pageId" => $page_id
	]);
$banned_persons = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$nb_banned_persons = COUNT($banned_persons);


$maRequete = $pdo->prepare("SELECT `user_id` FROM `followers` WHERE `page_id` = :pageId AND `user_id` = :userId AND `banned` = 'yes';");
	$maRequete->execute([
		":pageId" => $page_id,
		":userId" => $user_id
	]);
$user_is_banned = $maRequete->fetchAll(PDO::FETCH_ASSOC);
if (COUNT($user_is_banned)>0){
	$is_banned = TRUE;
} else {
	$is_banned = FALSE;
}

$banned_accounts = array();
foreach ($banned_persons as $banned_person) {
	$maRequete = $pdo->prepare("SELECT `user_id`, `first_name`, `last_name`, `profil_picture` FROM `users` WHERE `user_id` = :Id;");
		$maRequete->execute([
			":Id" => $banned_person['user_id']
		]);
		$maRequete->setFetchMode(PDO::FETCH_ASSOC);
	array_push($banned_accounts, $maRequete->fetch());
}


// checking whether we're friends with the person
$profile_id = filter_input(INPUT_POST, "profil_id");

$maRequete = $pdo->prepare("SELECT `user_id_a`, `user_id_b`, `status`, `blocked` FROM `relationships` WHERE ((`user_id_a` = :profile_id AND `user_id_b` = :userId) OR (`user_id_b` = :profile_id AND `user_id_a` = :userId)) AND `status`='approved';");
        $maRequete->execute([
            ":profile_id" => $profile_id,
			":userId" => $_SESSION["user"]["user_id"]
        ]);
	$profile_friend = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// pending friend requests
$maRequete = $pdo->prepare("SELECT `user_id_a`, `user_id_b`, `status`, `blocked` FROM `relationships` WHERE ((`user_id_a` = :profile_id AND `user_id_b` = :userId) OR (`user_id_b` = :profile_id AND `user_id_a` = :userId) AND `status`='pending');");
	$maRequete->execute([
		":profile_id" => $profile_id,
		":userId" => $_SESSION["user"]["user_id"]
	]);
$profile_friend_request = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = :userId");
    $maRequete->execute([
        ":userId" => $user_id
    ]);
    $user_likes = $maRequete->fetchAll(PDO::FETCH_ASSOC);
    $like = "unlike.png";

require_once __DIR__ . "/../html_partial/public_page.php";
$content = ob_get_clean();
?>