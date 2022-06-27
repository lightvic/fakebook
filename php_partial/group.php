<?php
ob_start();

require_once __DIR__ . "/../database/pdo.php";  // accessing the database

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$group_id = filter_input(INPUT_POST, "group_id");
} else if (isset($_SESSION["group"])) {
	$group_id = $_SESSION["group"]["group_id"];
} else {
	$message = "Consultez un groupe avant de revenir ici";
	//indique que le serveur refuse d'autoriser la requête 
	http_response_code(403);
	//j'appelle ma bannière html pour afficher un message d'erreur
	require_once __DIR__ . "/../html_partial/alert/banniere.php";
	exit;
}
$user_id = $_SESSION["user"]["user_id"];

// updating $_SESSION["group"]
$maRequete = $pdo->prepare(
	"SELECT * FROM `groups` WHERE `group_id` = :groupId;");
	$maRequete->execute([
		":groupId" => $group_id
	]);
$current_group = $maRequete->fetch();
$_SESSION["group"] = $current_group;

$group_id = $_SESSION["group"]["group_id"];
$group = $_SESSION["group"];

// displaying the group's name and its past articles
$title = "Fakebook - group " . $group["name"];
$h1 = $group["name"];
$maRequete = $pdo->prepare("SELECT * FROM `articles` WHERE `group_id` = :groupId ORDER BY `date` DESC");
$maRequete->execute([
	":groupId" => $group_id
]);
$articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// getting the group's stats
$nb_articles = Count($articles);

$maRequete = $pdo->prepare("SELECT `member_id`, `user_id` FROM `members` WHERE `group_id` = :groupId AND `status` = 'approved' AND `banned` = 'no';");
	$maRequete->execute([
		":groupId" => $group_id
	]);
$members = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$nb_members = COUNT($members);


$maRequete = $pdo->prepare("SELECT `user_id` FROM `members` WHERE `group_id` = :groupId AND `user_id` = :userId AND `status` = 'approved';");
	$maRequete->execute([
		":groupId" => $group_id,
		":userId" => $user_id
	]);
$user_is_member = $maRequete->fetchAll(PDO::FETCH_ASSOC);
if (COUNT($user_is_member)>0){
	$is_member = TRUE;
} else {
	$is_member = FALSE;
}

$accounts = array();
foreach ($members as $member) {
	$maRequete = $pdo->prepare("SELECT `user_id`, `first_name`, `last_name`, `profil_picture`,`status` FROM `users` WHERE `user_id` = :Id;");
		$maRequete->execute([
			":Id" => $member['user_id']
		]);
		$maRequete->setFetchMode(PDO::FETCH_ASSOC);
	array_push($accounts, $maRequete->fetch());
}

// getting the pending requests on the group
$maRequete = $pdo->prepare("SELECT `user_id`, `member_id` FROM `members` WHERE `group_id` = :groupId AND `status` = 'pending' AND `banned` = 'no';");
	$maRequete->execute([
		":groupId" => $group_id
	]);
$requests = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$request_accounts = array();
foreach ($requests as $request) {
	$maRequete = $pdo->prepare("SELECT `user_id`, `first_name`, `last_name`, `profil_picture` FROM `users` WHERE `user_id` = :Id;");
		$maRequete->execute([
			":Id" => $request['user_id']
		]);
		$maRequete->setFetchMode(PDO::FETCH_ASSOC);
	array_push($request_accounts, $maRequete->fetch());
}

// getting the group's admins
$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `group_id` = :groupId;");
	$maRequete->execute([
		":groupId" => $group_id
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


// getting those who were banned from the group (and checking whether the user himself is banned)

$maRequete = $pdo->prepare("SELECT `member_id`, `user_id` FROM `members` WHERE `group_id` = :groupId AND `banned` = 'yes';");
	$maRequete->execute([
		":groupId" => $group_id
	]);
$banned_persons = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$nb_banned_persons = COUNT($banned_persons);


$maRequete = $pdo->prepare("SELECT `user_id` FROM `members` WHERE `group_id` = :groupId AND `user_id` = :userId AND `banned` = 'yes';");
	$maRequete->execute([
		":groupId" => $group_id,
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



// getting all the user's friends and their infos
$maRequete = $pdo->prepare(
	"SELECT `user_id`, `first_name`, `last_name`, `profil_picture` FROM `users`
	WHERE `user_id` IN (SELECT `user_id_a` FROM `relationships` WHERE `user_id_b` = :userId AND `status`='approved')
	OR`user_id` IN (SELECT `user_id_b` FROM `relationships` WHERE `user_id_a` = :userId AND `status`='approved')");
        $maRequete->execute([
			":userId" => $_SESSION["user"]["user_id"]
        ]);
	$friends = $maRequete->fetchAll(PDO::FETCH_ASSOC);


// pending requests
$maRequete = $pdo->prepare("SELECT `status` FROM `members` WHERE `group_id` = :groupId AND `user_id` = :userId AND `status` = 'pending'");
	$maRequete->execute([
		":groupId" => $group_id,
		":userId" => $_SESSION["user"]["user_id"]
	]);
$user_pending = $maRequete->fetch();

if ($user_pending) {
	$user_pending_request = TRUE;
} else {
	$user_pending_request = FALSE;
};
// pending invites
$maRequete = $pdo->prepare("SELECT `user_id` FROM `members` WHERE `group_id` = :groupId");
	$maRequete->execute([
		":groupId" => $group_id
	]);
$all_members = $maRequete->fetchAll(PDO::FETCH_ASSOC);


// getting the likes
$maRequete = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = :userId");
    $maRequete->execute([
        ":userId" => $user_id
    ]);
    $user_likes = $maRequete->fetchAll(PDO::FETCH_ASSOC);
    $like = "unlike.png";

require_once __DIR__ . "/../html_partial/group.php";
$content = ob_get_clean();
?>