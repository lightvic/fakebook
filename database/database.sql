CREATE DATABASE IF NOT EXISTS `db_fakebook_ajltvv` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use db_fakebook_ajltvv;

CREATE TABLE IF NOT EXISTS `users` (
	`user_id` INT NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(256) NOT NULL,
	`first_name` VARCHAR(255),
	`last_name` VARCHAR(255),
	`profil_picture` VARCHAR(255) DEFAULT "default_profile_pic.png",
	`banner` VARCHAR(255) DEFAULT "default_banner.jpg",
	`status` ENUM('active', 'inactive') DEFAULT 'active',
	`theme` TINYINT DEFAULT 0,
	PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `pages` (
	`page_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`picture` VARCHAR(255) DEFAULT "default_page_pic.jpg",
	`banner` VARCHAR(255) DEFAULT "default_banner.jpg",
	`description` TEXT,
	`creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`page_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `groups` (
	`group_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`picture` VARCHAR(255) DEFAULT "default_page_pic.jpg",
	`banner` VARCHAR(255) DEFAULT "default_banner.jpg",
	`description` TEXT,
	`creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`status` ENUM('public', 'private') DEFAULT 'public',
	PRIMARY KEY (`group_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `articles` (
	`article_id` INT NOT NULL AUTO_INCREMENT,
	`content` TEXT,
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`picture` VARCHAR(255),
	`like_count` INT DEFAULT 0,
	`user_id` INT NOT NULL,
	`group_id` INT,
	`page_id` INT,
	PRIMARY KEY (`article_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`page_id`) REFERENCES `pages`(`page_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES `groups`(`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `comments` (
	`comment_id` INT NOT NULL AUTO_INCREMENT,
	`content` TEXT NOT NULL,
	`picture` VARCHAR(255),
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`like_count` INT DEFAULT 0,
	`article_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`uuid` VARCHAR(255),
	PRIMARY KEY (`comment_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`article_id`) REFERENCES `articles`(`article_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `likes` (
	`like_id` INT NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP, -- needed to date the notifications
	`article_id` INT,
	`comment_id` INT,
	`user_id` INT NOT NULL,
	`uuid` VARCHAR(255),
	PRIMARY KEY (`like_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`article_id`) REFERENCES `articles`(`article_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`comment_id`) REFERENCES `comments`(`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `admins` (
	`admin_id` INT NOT NULL AUTO_INCREMENT,
	`group_id` INT,
	`page_id` INT,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`admin_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`page_id`) REFERENCES `pages`(`page_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES `groups`(`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `members` (
	`member_id` INT NOT NULL AUTO_INCREMENT,
	`banned` ENUM('yes', 'no') DEFAULT 'no',
	`status` ENUM('pending','approved','invite') DEFAULT 'pending',
	`group_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`member_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES `groups`(`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `followers` (
	`follower_id` INT NOT NULL AUTO_INCREMENT,
	`banned` ENUM('yes', 'no') DEFAULT 'no',
	`page_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`follower_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`page_id`) REFERENCES `pages`(`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `relationships` (
	`relation_id` INT NOT NULL AUTO_INCREMENT,
	`status` ENUM('pending','approved') DEFAULT 'pending',
	`blocked` ENUM('yes', 'no') DEFAULT 'no',
	`user_id_a` INT NOT NULL,
	`user_id_b` INT NOT NULL,
	PRIMARY KEY (`relation_id`),
	CONSTRAINT FOREIGN KEY (`user_id_a`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`user_id_b`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `chats` (
	`chat_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(20) DEFAULT 'Nouvelle discussion',
	`chat_pic` VARCHAR(255) DEFAULT "default_page_pic.jpg",
	`uuid` VARCHAR(255),
	PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `messages` (
	`message_id` INT NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`content` TEXT,
	`picture` VARCHAR(255),
	`chat_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`message_id`),
	CONSTRAINT FOREIGN KEY (`chat_id`) REFERENCES `chats`(`chat_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `chat_members` (
	`chat_member_id` INT NOT NULL AUTO_INCREMENT,
	`chat_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`chat_member_id`),
	CONSTRAINT FOREIGN KEY (`chat_id`) REFERENCES `chats`(`chat_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `notifications` (
	`notif_id` INT NOT NULL AUTO_INCREMENT,
	`date` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`type` ENUM('article','like','comment','relationship_request','relationship_agree','relationship_disagree','join_group_request','join_group_agree','join_group_disagree') NOT NULL,
	`seen` ENUM('yes','no') NOT NULL,
	`user_id` INT,
	`article_id` INT,
	`like_id` INT,
	`comment_id` INT,
	`page_id` INT,
	`group_id` INT,
	PRIMARY KEY (`notif_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`article_id`) REFERENCES `articles`(`article_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`like_id`) REFERENCES `likes`(`like_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`comment_id`) REFERENCES `comments`(`comment_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`page_id`) REFERENCES `pages`(`page_id`) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES `groups`(`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `stats` (
	`stat_id` INT NOT NULL AUTO_INCREMENT,
	`nb_articles` INT DEFAULT 0,
	`nb_comments` INT DEFAULT 0,
	`nb_likes` INT DEFAULT 0,
	`likes_on_articles` INT DEFAULT 0,
	`likes_on_comments` INT DEFAULT 0,
	`comments_on_articles` INT DEFAULT 0,
	`nb_friends` INT DEFAULT 0,
	`user_id` INT NOT NULL,
	PRIMARY KEY (`stat_id`),
	CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

