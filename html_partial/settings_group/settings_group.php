<form id="goTogroup" action="/group" method="post">
	<input type="hidden" name="group_id" value="<?= $_SESSION["group"]["group_id"] ?>" />
	<button class="baseProfile" type="submit" id="picture" style="border:0; padding:5px;">
		<img id="profilPic" src="img_pages_groups/<?= $_SESSION["group"]["picture"] ?>" alt="" width="40px">
	</button>
	<button class="baseProfile" type="submit" id="first_name" style="border:0; padding:0;"> 
		Retour à <?= $_SESSION["group"]["name"] ?> 
	</button>
</form>
<h1><?= $h1 ?></h1>
<section>
    <img id="" src="img_pages_groups/<?= $picture ?>" alt="" width="40px">
    <form action="/edit_group_photo" id="edit_picture" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_picture" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_picture">Valider</button>
    </form>
    <img id="" src="img_baniere/<?= $banner ?>" alt="" width=" 200px">
    <form action="/edit_group_banner" id="edit_banniere" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_ban" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_banniere">Valider</button>
    </form>
    <div>
        <h3>Nom</h3>
        <form id="new_name" method="post" action="/new_group_name">
            <input type="text" name="modify_name" value="<?= $name ?>">
            <button class="modify" type="submit">modifier</button>
        </form>
        <h3>Description</h3>
        <form id="new_description" method="post" action="/new_group_description">
            <input type="text" name="modify_description" value="<?= $description ?>">
            <button class="modify" type="submit">modifier</button>
        </form>
		<h3>Confidentialité</h3>
		<?php if($_SESSION["group"]["status"] === 'private') : ?>
		<span>Le groupe est actuellement privé.</span>
		<?php elseif($_SESSION["group"]["status"] === 'public') : ?>
		<span>Le groupe est actuellement public.</span>
		<?php else : ?>
		<span>ERREUR DE IF</span>
		<?php endif; ?>
		<form id="edit_group_status" method="post" action="/edit_group_status">
			<label id="modify_status_label" for="modify_status">Choisissez la confidentialité de votre groupe :</label><br>
			<input id="public" name="modify_status" type="radio" value="public">Public</input>
			<input id="private" name="modify_status" type="radio" value="private">Privé</input>
			<button class="modify" type="submit" style="margin-left:55px;">Valider</button>
		</form>
    </div>
