<?php
require_once "../database/pdo.php";
// get the comment by article
$maRequete = $pdo->prepare("SELECT * FROM `comments` WHERE `article_id` = :articleId ORDER BY `date`");
    $maRequete->execute([
        ":articleId" => $article["article_id"]
    ]);
    $comments = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// get information about every users
$maRequete = $pdo->prepare("SELECT `user_id`, `profil_picture`, `first_name`, `last_name` FROM `users` ");
    $maRequete->execute();
    $comment_profiles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

// get every likes
$maRequete = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = :userId");
    $maRequete->execute([
        ":userId" => $user_id
    ]);
    $comment_user_likes = $maRequete->fetchAll(PDO::FETCH_ASSOC);
    $comment_like = "unlike.png";





// taking groups and pages into account

// first we need to check if the article is in a page or a group so we get the article's informations :
$maRequete = $pdo->prepare("SELECT `group_id`, `page_id` FROM `articles` WHERE `article_id` = :articleId");
    $maRequete->execute([
        ":articleId" => $article["article_id"]
    ]);
    $group_or_page = $maRequete->fetch();

if ($group_or_page["page_id"] !== NULL) {
	// then we get the page's info if needed
	$maRequete = $pdo->prepare("SELECT `page_id`, `picture`, `name` FROM `pages` WHERE `page_id` IN (SELECT `page_id` FROM `articles` WHERE `article_id` = :articleId)");
		$maRequete->execute([
			":articleId" => $article["article_id"]
		]);
		$article_page = $maRequete->fetch();
	// then we get all admins from that page
	$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `page_id` IN (SELECT `page_id` FROM `articles` WHERE `article_id` = :articleId)");
		$maRequete->execute([
			":articleId" => $article["article_id"]
		]);
		$article_page_admins = $maRequete->fetchALL(PDO::FETCH_ASSOC);
};

if ($group_or_page["group_id"] !== NULL) {
	// then we get the group's info if needed
	$maRequete = $pdo->prepare("SELECT `group_id`, `picture`, `name` FROM `groups` WHERE `group_id` IN (SELECT `group_id` FROM `articles` WHERE `article_id` = :articleId)");
		$maRequete->execute([
			":articleId" => $article["article_id"]
		]);
		$article_group = $maRequete->fetch();
	// then we get all admins from that group
	$maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `group_id` IN (SELECT `group_id` FROM `articles` WHERE `article_id` = :articleId)");
		$maRequete->execute([
			":articleId" => $article["article_id"]
		]);
		$article_group_admins = $maRequete->fetchALL(PDO::FETCH_ASSOC);
};






require __DIR__ . "/../html_partial/comment.php";