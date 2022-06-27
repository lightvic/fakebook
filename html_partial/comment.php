<div id="newComment">
    <!-- Form new article-->
    <form id="newcommentForm" method="post" action="/new_comment">
        <label id="commentLabel" for="commentInput">Ecrivez votre commentaire</label><br>
        <textarea id="commentInput" name="commentInput" type="text"></textarea>
        <input type="hidden" id="new_comment_input" name="article_id" value="<?= $article["article_id"] ?>">
        <button type="submit" id="submitPublication">Envoyer</button>
    </form>
</div>
<!-- show comments of the article and show likes of the comment -->
<?php foreach ($comments as $comment) :
    foreach ($comment_profiles as $comment_profile) {
        if ($comment_profile["user_id"] === $comment["user_id"]) {
            $profil_picture = $comment_profile["profil_picture"];
            $first_name = $comment_profile["first_name"];
            $last_name = $comment_profile["last_name"];
        }
        foreach ($comment_user_likes as $comment_user_like) {
            if ($comment_user_like["comment_id"] === $comment["comment_id"]) {
                $comment_like = "like.png";
                break;
            } else {
                $comment_like = "unlike.png";
            }
        }
    } ?>
	<!-- just like articles, comments are shown differently depending on the writer -->
	<!-- if it's an admin of the page/group who posted the original article, the comment is shown as that of the page/group -->
	<!-- in any other case, the comment is shown as that of the person -->
	<?php
	if ($group_or_page["group_id"] !== NULL) { // if it's a group's article
		$comment_from_group_admin = false;
		foreach ($article_group_admins as $article_group_admin){
			if ($article_group_admin["user_id"] === $comment["user_id"]) {
				$comment_from_group_admin = true;
				break;
			}
		}
		$i_am_group_admin = false;
		foreach ($article_group_admins as $article_group_admin){
			if ($article_group_admin["user_id"] === $_SESSION["user"]["user_id"]) {
				$i_am_group_admin = true;
				break;
			}
		}
		if($comment_from_group_admin){
			$show_name = $article_group["name"];
			$show_picture =  "img_pages_groups/" . $article_group["picture"];
			$action = "/group";
			$actionId = "goTogroup";
			$action_name = "group_id";
			$action_value = $article_group["group_id"];
		} else { // writer's own comment
			$show_name = $first_name . " " . $last_name;
			$show_picture = "img_profil/" . $profil_picture;
			$action = "/profile";
			$actionId = "goToProfile";
			$action_name = "profil_id";
			$action_value = $comment["user_id"];
		}
	} elseif ($group_or_page["page_id"] !== NULL) { // if it's a page's article
		// we check if the user is an admin on that page
		$comment_from_page_admin = false;
		foreach ($article_page_admins as $article_page_admin){
			if ($article_page_admin["user_id"] === $comment["user_id"]) {
				$comment_from_page_admin = true;
				break;
			}
		}
		$i_am_page_admin = false;
		foreach ($article_page_admins as $article_page_admin){
			if ($article_page_admin["user_id"] === $_SESSION["user"]["user_id"]) {
				$i_am_page_admin = true;
				break;
			}
		}
		if($comment_from_page_admin) {
			$show_name = $article_page["name"];
			$show_picture =  "img_pages_groups/" . $article_page["picture"];
			$action = "/public_page";
			$actionId = "goToPage";
			$action_name = "page_id";
			$action_value = $article_page["page_id"];
		} else { // writer's own comment
			$show_name = $first_name . " " . $last_name;
			$show_picture = "img_profil/" . $profil_picture;
			$action = "/profile";
			$actionId = "goToProfile";
			$action_name = "profil_id";
			$action_value = $comment["user_id"];
		}
	} else { // writer's own comment
		$show_name = $first_name . " " . $last_name;
		$show_picture = "img_profil/" . $profil_picture;
		$action = "/profile";
		$actionId = "goToProfile";
		$action_name = "profil_id";
		$action_value = $comment["user_id"];
	} ?>
    <div id="comment" style="margin-top:20px; border: solid 1px black; padding: 10px; width: 300px">
        <form id="<?= $actionId ?>" action="<?= $action ?>" method="post">
            <input type="hidden" name="<?= $action_name ?>" value="<?= $action_value ?>" />
            <button class="articleColor" type="submit" id="comment_profil_picture" style="border:0; padding:5px;">
                <img id="profilPic" src="<?= $show_picture ?>" alt="" width="30px">
            </button>
            <button class="articleColor" type="submit" id="comment_name" style="border:0; padding:0;">
			<?= $show_name ?>
            </button>
        </form>
        <span id="date"><?= $comment["date"] ?></span>
        <br>
        <span id="data"><?= $comment["content"] ?></span>
        <form action="/like_comment" method="post" id="like_comment">
            <button class="articleColor" id="like_btn" type="submit" style="border: 0; padding:0px; margin: 5px;">
                <img style=" width: 40px; height: 40px; margin: 0px;" src="img_ressources/<?= $comment_like ?>" alt="">
            </button>
            <span><?=$comment["like_count"]?></span>
            <input type="hidden" name="like_comment_id" value="<?= $comment["comment_id"] ?>">
        </form>
		<!-- admins can modify/delete their fellow admins' comments-->
        <?php if ($comment["user_id"] === $_SESSION["user"]["user_id"] || ($group_or_page["page_id"] !== NULL && $i_am_page_admin) || ($group_or_page["group_id"] !== NULL && $i_am_group_admin)) : ?>
            <form id="delete_comment" method="post" action="/delete_comment">
                <button type="submit" id="delete_btn_comment">Supprimer</button>
                <input type="hidden" name="comment_id" value="<?= $comment["comment_id"] ?>">
                <input type="hidden" name="comment_user" value="<?= $comment["user_id"] ?>">
            </form>
        <?php endif ?>
        <?php if ($comment["user_id"] === $_SESSION["user"]["user_id"] || ($group_or_page["page_id"] !== NULL && $comment_from_page_admin && $i_am_page_admin) || ($group_or_page["group_id"] !== NULL && $comment_from_group_admin && $i_am_group_admin)) : ?>
            <button type="button" id="open_modify_comment">Modifier</button>
            <form id="form_modify_comment" method="post" action="/modify_comment">
                <label id="label_modify_comment" for="modify_comment_input">Ecrivez votre message</label>
                <textarea id="modify_comment_input" type="text" name="modify_comment" value=""><?= $comment["content"] ?></textarea>
                <button id="modify_btn_comment" type="submit">Valider</button>
                <input type="hidden" name="comment_id" value="<?= $comment["comment_id"] ?>">
                <input type="hidden" name="comment_user" value="<?= $comment["user_id"] ?>">
            </form>
        <?php endif ?>

    </div>
<?php endforeach; ?>