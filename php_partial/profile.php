<?php
ob_start();

require_once __DIR__ . "/../database/pdo.php";  // accessing the database
$user_id = $_SESSION["user"]["user_id"];
// if we got on the page with the url (without following a link), we end up on our own profile page
if($_SERVER["REQUEST_METHOD"] === "GET") {
    if(isset($_SESSION["profile_watching"])) {
        $profile_id = $_SESSION["profile_watching"]["user_id"]; // needed to check whether it's the user's page or someone else's.
        $profile = $_SESSION["profile_watching"];
    } else {
        $profile_id = $_SESSION["user"]["user_id"];
        $profile = $_SESSION["user"];
    }
}

// if we got on the page by clicking a link (someone's name or pic), we end up on the perso's page
if($_SERVER["REQUEST_METHOD"] === "POST") {
	$profile_id = filter_input(INPUT_POST, "profil_id");

    $maRequete = $pdo->prepare("SELECT `user_id`, `email`, `password`, `first_name`, `last_name`, `profil_picture`, `banner`, `status` FROM `users` WHERE `user_id` = :profile_id;");
        $maRequete->execute([
            ":profile_id" => $profile_id
        ]);
	$profile = $maRequete->fetch();
    $_SESSION["profile_watching"] = $profile;
}

// displaying the profile's owner name and his past articles
$title = "Fakebook - Profil de " . $profile["first_name"] . " " . $profile["last_name"];
$h1 = $profile["first_name"] . " " . $profile["last_name"];
$maRequete = $pdo->prepare("SELECT * FROM `articles` WHERE `user_id` = :profile_id ORDER BY `date` DESC ;");
$maRequete->execute([
	":profile_id" => $profile_id
]);
$articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// getting the profile's stats
$maRequete = $pdo->prepare("SELECT `user_id`, `nb_friends`, `nb_articles`, `nb_comments`, `nb_likes`, `comments_on_articles`, `likes_on_articles`, `likes_on_comments` FROM `stats` WHERE `user_id` = :profile_id;");
	$maRequete->execute([
		":profile_id" => $profile_id
	]);
$profile_stats = $maRequete->fetch();

// checking whether we're friends with the person

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

// get the pages the person follows
$maRequete = $pdo->prepare("SELECT `page_id`, `name`, `picture` FROM `pages` WHERE `page_id` IN (SELECT `page_id` FROM `followers` WHERE `user_id` = :profile_id)");
    $maRequete->execute([
		":profile_id" => $profile_id
    ]);
    $pages = $maRequete->fetchAll(PDO::FETCH_ASSOC);
// get the groups a member of which the person is
$maRequete = $pdo->prepare("SELECT `group_id`, `name`, `picture` FROM `groups` WHERE `group_id` IN (SELECT `group_id` FROM `members` WHERE `user_id` = :profile_id AND `status` = 'approved')");
    $maRequete->execute([
		":profile_id" => $profile_id
    ]);
    $groups = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT `group_id`, `name`, `picture` FROM `groups` WHERE `group_id` IN (SELECT `group_id` FROM `members` WHERE `user_id` = :profile_id AND `status` = 'invite')");
$maRequete->execute([
    ":profile_id" => $profile_id
]);
$groups_invite = $maRequete->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . "/../html_partial/profile.php";
$content = ob_get_clean();
?>