
<section>
	<!-- page top : profile picture, first & last name -->
	<div class="bannerSize" style="background-image: url(img_baniere/<?= $page["banner"] ?>)"></div>
</section>
<h1 id="h1"><img id="profilPic" src="img_pages_groups/<?= $page["picture"] ?>" alt="" width="40px"><span id="profileName"><?=$h1?></span></h1>
<!-- stats -->
<section> 
<div id="description"><?=$page["description"]?></div> <!-- changer le style de police dans le css -->
<?php if ($is_admin) :?>
	<button style="margin-bottom: 50px; margin-top: 7px;"><a style="text-decoration: none; color: black;" href="/settings_public_page">Paramètres</a></button>
<?php endif; ?>
<div><?=$page["name"]?> compte <?=$nb_articles?> article(s).</div>
<div><?=$page["name"]?> est suivie par <?=$nb_followers?> personne(s).</div>
</section> <br> <!-- we will remove this br when the css is done-->
<section>
	<div>
	<!-- interactions -->
		<?php if ($is_banned === false) : ?>	
			<!-- You can't do anything if you're banned. -->
			<?php if ($is_follower) :?>
				<!-- unfollow -->
				<?php if($is_admin) :?>
					<div style='width: 450px;'>Cette page compte <?=$nb_admins?> administrateur(s). Si vous ne souhaitez plus occuper cette fonction et que vous êtes le dernier, la page sera supprimée.</div>
					<form action="/remove_admin" class="form" method="post" >
						<button type="submit" id="remove_admin" name="remove_admin">
							Ne plus être admin
						</button>
						<input type="hidden" id="input_remove_admin">
					</form>
				<?php else :?>
					<form action="/unfollow" class="form" method="post" >
						<button type="submit" id="unfollow" name="unfollow">
							Ne plus suivre cette page
						</button>
						<input type="hidden" name="unfollow" value="<?= $page_id ?>">
						<input type="hidden" id="input_unfollow">
					</form>
				<?php endif;?>
			<?php else :?>
				<form action="/follow" class="form" method="post" >
					<button type="submit" id="follow" name="follow">
						Suivre cette page
					</button>
					<input type="hidden" name="follow" value="<?= $page_id ?>">
				</form>
			<?php endif ?>
		<?php endif ?>
	</div>
</section>
<section>
	<!-- main page -->
	<div>
	<!-- articles -->
		<div>
		<!-- new article -->
			<!-- Form new article: needs to be modified to match a page -->
			<?php if ($is_admin) :?>
			<form id="newPublicationForm" method="post" enctype="multipart/form-data" action="/new_article_page">
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
				<?php foreach ($articles as $article): ?>
					<?php
					foreach ($user_likes as $user_like) {
						if ($user_like["article_id"] === $article["article_id"]) {
							$like = "like.png";
							break;
						} else {
							$like = "unlike.png";
						}
					} ?>
					<div id="article" style="margin-top:20px; border: solid 1px black; padding: 10px; width: 500px">
						<form id="goToPage" action="/public_page" method="post"> <!-- needs to be modified to match a page -->
							<input type="hidden" name="page_id" value="<?= $page_id ?>" />
							<button type="submit" class="articleColor" id="profil_picture" style="border:0; padding:5px;">
								<img id="profilPic" src="img_pages_groups/<?= $page["picture"] ?>" alt="" width="40px">
							</button>
							<button type="submit" class="articleColor" id="first_name" style="border:0; padding:0;"> 
								<?= $page["name"] ?> 
							</button>
						</form>
						<span id="date"><?= $article["date"] ?></span>
						<br>
						<span id="data"><?= $article["content"] ?></span>
						<br>
						<?php if($article["picture"]) :?>
							<img id="image_article" width="300px" src="img_post/<?=$article["picture"]?>" >
						<?php endif; ?>
						<?php if($is_admin) :?>
							<form id="delete_article" method="post" action="/delete_article">
								<button type="submit" id="delete_btn">Supprimer</button>
								<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
								<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
							</form>
							<button type="button" id="open_modify_article">Modifier</button>
							<form id="form_modify_article" method="post" action="/modify_article">
								<label id="label_modify" for="modify_input">Ecrivez votre message</label>
								<textarea id="modify_article_input" type="text" name="modify_article" value=""><?= $article["content"] ?></textarea>
								<button id="modify_btn" type="submit">Valider</button>
								<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
								<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
							</form>
						<?php endif ?>
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
			<?php else: ?>
				<span>Vous avez été banni(e) de cette page. Vous ne pouvez donc plus voir son contenu.</span>
			<?php endif; ?>
		</div>
	</div>
	<div>
		<?php if($is_follower && $is_banned === false):?>
			<!-- showing the list of followers, with a link to their profile -->
			<button type="button" id="open_followers_list">Afficher les followers</button>
			<section id="followers_list" style="display: none">
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

					<?php foreach($admins as $admin) :?>
						<?php if($admin["user_id"] === $account["user_id"]) { 
							$account_admin = true;
							break;
						} else { 
							$account_admin = false;
						}?>
					<?php endforeach; ?>
					<?php if($is_admin && $account_admin === false):?>
						<form action="/add_admin" class="form" method="post" >
							<button type="submit" id="new_admin" name="new_admin">
								Ajouter comme admin
							</button>
							<input type="hidden" name="new_admin_page" value="<?= $page_id ?>">
							<input type="hidden" name="new_admin_account" value="<?= $account["user_id"] ?>">
						</form>
						<?php if($account["user_id"] !== $user_id):?>
							<form action="/ban" class="form" method="post" >
								<button type="submit" id="ban" name="ban">
									Bannir cette personne
								</button>
								<input type="hidden" name="ban_page" value="<?= $page_id ?>">
								<input type="hidden" name="ban_account" value="<?= $account["user_id"] ?>">
							</form>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach; ?>
			<?php endif ?>
		</section>
		<?php if($is_admin):?>
			<!-- showing the list of banned followers, with a link to their profile and a button to unban them -->
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
					<form action="/unban" class="form" method="post" >
						<button type="submit" id="unban" name="unban">
							Annuler le ban
						</button>
						<input type="hidden" name="unban_page" value="<?= $page_id ?>">
						<input type="hidden" name="unban_account" value="<?= $banned_account["user_id"] ?>">
					</form>
				<?php endforeach; ?>
			<?php endif ?>
		</section>
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
