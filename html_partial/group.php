
<section>
	<!-- group top : profile picture, first & last name -->
	<div class="bannerSize" style="background-image: url(img_baniere/<?= $group["banner"] ?>)"></div>
</section>
<h1 id="h1"><img id="profilPic" src="img_pages_groups/<?= $group["picture"] ?>" alt="" width="40px"><span id="profileName"><?=$h1?></span></h1>
<!-- stats -->
<section> 
<div id="description"><?=$group["description"]?></div> <!-- changer le style de police dans le css -->
<?php if ($is_admin) :?>
	<button style="margin-bottom: 50px; margin-top: 7px;"><a style="text-decoration: none; color: black;" href="/settings_group">Paramètres</a></button>
<?php endif; ?>
<div><?=$group["name"]?> compte <?=$nb_articles?> article(s) et <?=$nb_members?> membre(s).</div>
<?php if ($group["status"]==='private') : ?>
	<div style='width: 450px;'>Ce groupe est privé. Seuls les membres peuvent donc en voir le contenu, et toute demande de rejoindre le groupe doit être approuvée par un admin.</div>
<?php endif; ?>
</section> <br> <!-- we will remove this br when the css is done-->
<section>
	<div>
	<!-- interactions -->
		<?php if ($is_banned === false) : ?>	
			<!-- You can't do anything if you're banned. -->
			<?php if ($is_member) :?>
				<!-- member_removal -->
				<?php if($is_admin) :?>
					<div style='width: 450px;'>Ce groupe compte <?=$nb_admins?> administrateur(s). Si vous ne souhaitez plus occuper cette fonction et que vous êtes le dernier, le groupe sera supprimé.</div>
					<form action="/remove_admin_group" class="form" method="post" >
						<button type="submit" id="remove_admin" name="remove_admin">
							Ne plus être admin
						</button>
						<input type="hidden" id="input_remove_admin">
					</form>
				<?php else :?>
					<form action="/member_removal" class="form" method="post" >
						<button type="submit" id="member_removal" name="member_removal">
							Quitter le groupe
						</button>
						<input type="hidden" name="member_removal_group" value="<?= $group["group_id"] ?>" />
						<input type="hidden" name="member_removal_account" value="<?= $user_id ?>" />
						<input type="hidden" id="input_member_removal">
					</form>
				<?php endif;?>
				<!-- i leave that here because we may use it later, and i don't want to type it again -->
				<!-- <form action="/start_chat" class="form" method="post" >
					<button type="submit" id="start_chat" name="start_chat">
						Démarrer la conversation
					</button>
					<input type="hidden" name="start_chat" value="<?= $user_id ?>">
				</form> -->
			<?php else :?>
				<?php if ($user_pending_request) :?>
					<form action="/member_removal" class="form" method="post" >
						<button type="submit" id="member_removal" name="member_removal">
							Annuler la demande
						</button>
						<input type="hidden" name="member_removal_group" value="<?= $group["group_id"] ?>" />
						<input type="hidden" name="member_removal_account" value="<?= $user_id ?>" />
					</form>
				<?php else : ?>
					<?php
					$member_or_invite =false;
					foreach ($all_members as $all_member) {
						if($all_member["user_id"] === $user_id) {
							$member_or_invite =true;
							break;
						}
					}
					?>
					<?php if(!$member_or_invite) : ?>
						<form action="/member_request" class="form" method="post" >
							<button type="submit" id="member_request" name="member_request">
								Rejoindre ce groupe
							</button>
						</form>
					<?php else : ?>
						<form id="invite_accepted" action="/invite_accepted" method="post">
							<input type="hidden" name="invite_group" value="<?= $group["group_id"] ?>" />
							<input type="hidden" name="invite_friend" value="<?= $user_id ?>" />
							<button type="submit" id="validate_invite" name="invite_accepted"> 
								Accepter l'invitation
							</button>
						</form>
						<form id="member_removal" action="/member_removal" method="post">
							<input type="hidden" name="member_removal_group" value="<?= $group["group_id"] ?>" />
							<input type="hidden" name="member_removal_account" value="<?= $user_id ?>" />
							<button type="submit" id="validate_member_removal" name="member_removal"> 
								Refuser l'invitation
							</button>
						</form>
					<?php endif; ?>
				<?php endif ?>
			<?php endif ?>
		<?php endif ?>
	</div>
</section>

<section>
	<!-- main group -->
	<div>
	<!-- articles -->
		<div>
		<!-- new article -->
			<!-- Form new article: needs to be modified to match a group -->
			<?php if ($is_member) :?>
			<form id="newPublicationForm" method="post" enctype="multipart/form-data" action="/new_article_group">
				<label id="publicationLabel" for="articleInput">Ecrivez votre message</label><br>
				<textarea id="articleInput" name="articleInput" type="text"></textarea>
				<div id="depose">Déposez vos images ou cliquez pour choisir</div>
				<input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg, image/png, image/gif, image/jpg">
				<div id="preview"></div>
				<button type="submit" id="submitPublication">Envoyer</button>
				<button id="cancel" type="button">Annuler</button>
			</form>
			<?php endif ?>
		</div>
		<div>
			<?php if ($is_banned === false) : ?>
				<!-- past articles. not visible if you are banned -->
				<?php if ($group["status"]==='public' || ($group["status"]==='private' && $is_member)) : ?>
					<!-- only members can see private groups' articles  -->
					<?php foreach ($articles as $article): ?>
						<!-- articles are shown differently depending on the writer -->
						<!-- if it's an admin, the article is shown as that of the group -->
						<!-- if it's only a member, the article is shown as that of the person -->
						<?php
						foreach ($user_likes as $user_like) {
							if ($user_like["article_id"] === $article["article_id"]) {
								$like = "like.png";
								break;
							} else {
								$like = "unlike.png";
							}
						}
						$writer_is_admin = false;
			
						foreach($admins as $admin) {
							if($admin["user_id"] === $article["user_id"]) {
								$writer_is_admin = true;
								break;
							}
						}
						foreach ($accounts as $account) { 
							if ($account["user_id"] === $article["user_id"]) {
								$profil_picture = $account["profil_picture"];
								$first_name = $account["first_name"];
								$last_name = $account["last_name"];
								$profile_status = $account["status"]; // needs to be taken into account !!!
							}
						}
						// articles
						if ($writer_is_admin === false ) { // writer's own article
							$show_name = $first_name . " " . $last_name;
							$show_picture = "img_profil/" . $profil_picture;
							$picture_id = "profil_picture";
							$action = "/profile";
							$actionId = "goToProfile";
							$action_name = "profil_id";
							$action_value = $article["user_id"];
				
						} else { // group's article (because it's written by an admin)
							$show_name = $group["name"];
							$show_picture =  "img_pages_groups/" . $group["picture"];
							$picture_id = "picture";
							$action = "/group";
							$actionId = "goTogroup";
							$action_name = "group_id";
							$action_value = $article["group_id"];
						}; ?>
						<div id="article" style="margin-top:20px; border: solid 1px black; padding: 10px; width: 500px">
							<form id="<?= $actionId ?>" action="<?= $action ?>" method="post"> <!-- needs to be modified to match a group -->
								<input type="hidden" name="<?= $action_name ?>" value="<?= $action_value ?>" />
								<button type="submit" class="articleColor" id="<?= $picture_id ?>" style="border:0; padding:5px;">
									<img id="profilPic" src="<?= $show_picture ?>" alt="" width="40px">
								</button>
								<button type="submit" class="articleColor" id="first_name" style="border:0; padding:0;"> 
									<?= $show_name ?> 
								</button>
							</form>
							<span id="date"><?= $article["date"] ?></span>
							<br>
							<span id="data"><?= $article["content"] ?></span>
							<br>
							<?php if($article["picture"]) :?>
								<img id="image_article" width="300px" src="img_post/<?=$article["picture"]?>" >
							<?php endif; ?>
							<?php if(($writer_is_admin && $is_admin) || (!$writer_is_admin && $article["user_id"] === $user_id)) :?>
								<!-- members can modify their own articles and admins can do the same for the group's articles-->
								<button type="button" id="open_modify_article">Modifier</button>
								<form id="form_modify_article" method="post" action="/modify_article">
									<label id="label_modify" for="modify_input">Ecrivez votre message</label>
									<textarea id="modify_article_input" type="text" name="modify_article" value=""><?= $article["content"] ?></textarea>
									<button id="modify_btn" type="submit">Valider</button>
									<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
									<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
								</form>
							<?php endif ?>
							<!-- members can delete their own articles and admins can do the same for any article -->
							<?php if($is_admin || (!$writer_is_admin && $article["user_id"] === $user_id)) : ?>
								<form id="delete_article" method="post" action="/delete_article">
									<button type="submit" id="delete_btn">Supprimer</button>
									<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
									<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
								</form>
							<?php endif; ?>
							<form action="/like_article" method="post" id="like_article">
								<button class="articleColor" id="like_btn" type="submit" style="border: 0; padding:0px; margin: 5px;">
									<img style=" width: 40px; height: 40px; margin: 0px;" src="img_ressources/<?= $like ?>" alt="">
								</button>
								<span><?=$article["like_count"]?></span>
								<input type="hidden" name="like_article_id" value="<?= $article["article_id"] ?>">
							</form>
							<button type="button" id="open_comment">Commenter</button>
							<section id="comment_section">
								<!-- require un truc ici -->
								<?php require __DIR__ . "/../php_partial/comment.php"?>
							</section>
						</div>
					<?php endforeach;?>
				<?php endif; ?>
			<?php else: ?>
				<span>Vous avez été banni(e) de ce groupe. Vous ne pouvez donc plus voir son contenu.</span>
			<?php endif; ?>
		</div>
	</div>
	
	<div>
		<?php if($is_member && $is_banned === false):?>
			<!-- showing the list of members, with a link to their profile -->
			<button type="button" id="open_members_list">Afficher les membres</button>
			<section id="members_list" style="display: none">
				<?php foreach ($accounts as $account) : ?>
					<form id="goToProfile" action="/profile" method="post">
						<input type="hidden" name="profil_id" value="<?= $account["user_id"] ?>" />
						<button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
							<img id="profilPic" src="img_profil/<?= $account["profil_picture"] ?>" alt="" width="40px">
						</button>
						<button type="submit" class="baseProfile" id="first_name" style="border:0; padding:0;"> 
							<?= $account["first_name"] . " " . $account["last_name"] ?> 
						</button>
					</form>
					<?php $account_admin = false;?>
					<?php foreach($admins as $admin) {
						if($admin["user_id"] === $account["user_id"]) { 
							$account_admin = true;
							break;
						};
					}?>
					<?php if($is_admin && $account_admin === false):?>
						<form action="/add_admin_group" class="form" method="post" >
							<button type="submit" id="new_admin" name="new_admin">
								Ajouter comme admin
							</button>
							<input type="hidden" name="new_admin_group" value="<?= $group_id ?>">
							<input type="hidden" name="new_admin_account" value="<?= $account["user_id"] ?>">
						</form>
						<?php if($account["user_id"] !== $user_id):?>
							<form action="/ban_group" class="form" method="post" >
								<button type="submit" id="ban" name="ban">
									Bannir cette personne
								</button>
								<input type="hidden" name="ban_group" value="<?= $group_id ?>">
								<input type="hidden" name="ban_account" value="<?= $account["user_id"] ?>">
							</form>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach; ?>
			</section>
			<!-- showing the list of friends who are not members yet, with a link to their profile and a button to invite them -->
			<button type="button" id="open_friends_list">Inviter des amis</button>
			<section id="friends_list" style="display: none">
				<?php foreach ($friends as $friend) : ?>
					<?php
					$friend_is_member =false;
					foreach ($all_members as $all_member) {
						if($all_member["user_id"] === $friend["user_id"]) {
							$friend_is_member =true;
							break;
						}
					}
					?>
					<?php if (!$friend_is_member) : ?>
						<form id="goToProfile" action="/profile" method="post">
							<input type="hidden" name="profil_id" value="<?= $friend["user_id"] ?>" />
							<button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
								<img id="profilPic" src="img_profil/<?= $friend["profil_picture"] ?>" alt="" width="40px">
							</button>
							<button type="submit" class="baseProfile" id="first_name" style="border:0; padding:0;"> 
								<?= $friend["first_name"] . " " . $friend["last_name"] ?> 
							</button>
						</form>
						<form action="/invite" class="form" method="post" >
							<button type="submit" id="invite" name="invite">
								Inviter à rejoindre le groupe
							</button>
							<input type="hidden" name="invite_group" value="<?= $group_id ?>">
							<input type="hidden" name="invite_friend" value="<?= $friend["user_id"] ?>">
						</form>
					<?php endif; ?>
				<?php endforeach; ?>
			</section>
		<?php endif ?>
		<?php if($is_admin && $_SESSION["group"]["status"] === "private"): ?>
			<!-- show list of those who wish to join the group -->
			<button type="button" id="open_requests_list">Afficher les demandes</button>
			<section id="requests_list" style="display: none">
				<?php foreach ($request_accounts as $request_account) : ?>
					<form id="goToProfile" action="/profile" method="post">
						<input type="hidden" name="profil_id" value="<?= $request_account["user_id"] ?>" />
						<button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
							<img id="profilPic" src="img_profil/<?= $request_account["profil_picture"] ?>" alt="" width="40px">
						</button>
						<button type="submit" class="baseProfile" id="first_name" style="border:0; padding:0;"> 
							<?= $request_account["first_name"] . " " . $request_account["last_name"] ?> 
						</button>
					</form>
					<form action="/member_approval" class="form" method="post" >
						<button type="submit" id="member_approval" name="member_approval">
							Accepter la demande
						</button>
						<input type="hidden" name="member_approval_user" value="<?= $request_account["user_id"] ?>">
						<input type="hidden" name="member_approval_group" value="<?= $group_id ?>">
					</form>
					<form action="/member_removal" class="form" method="post" >
						<button type="submit" id="member_removal" name="member_removal">
							Rejeter la demande
						</button>
						<input type="hidden" name="member_removal_group" value="<?= $group["group_id"] ?>" />
						<input type="hidden" name="member_removal_account" value="<?= $request_account["user_id"] ?>" />
					</form>
				<?php endforeach; ?>
			</section>
		<?php endif ?>
		</section>
		<?php if($is_admin):?>
			<!-- showing the list of banned members, with a link to their profile and a button to unban them -->
			<button type="button" id="open_banned_list">Afficher les bannis</button>
			<section id="banned_list" style="display: none">
				<?php foreach ($banned_accounts as $banned_account) : ?>
					<form id="goToProfile" action="/profile" method="post">
						<input type="hidden" name="profil_id" value="<?= $banned_account["user_id"] ?>" />
						<button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
							<img id="profilPic" src="img_profil/<?= $banned_account["profil_picture"] ?>" alt="" width="40px">
						</button>
						<button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
							<?= $banned_account["first_name"] . " " . $banned_account["last_name"] ?> 
						</button>
					</form>
					<form action="/unban_group" class="form" method="post" >
						<button type="submit" id="unban" name="unban">
							Annuler le ban
						</button>
						<input type="hidden" name="unban_group" value="<?= $group_id ?>">
						<input type="hidden" name="unban_account" value="<?= $banned_account["user_id"] ?>">
					</form>
				<?php endforeach; ?>
			</section>
		<?php endif ?>
	</div>
	<div>
	<!-- stats -->
		<div>
		<!-- articles published through time -->
		</div>
		<div>
		<!-- likes / the person put through time -->
		</div>
		<div>
		<!-- likes / the person obtained through time -->
		</div>
	</div>
</section>
