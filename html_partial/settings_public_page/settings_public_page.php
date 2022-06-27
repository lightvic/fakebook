<form id="goToPage" action="/public_page" method="post"> <!-- needs to be modified to match a page -->
	<input type="hidden" name="page_id" value="<?= $_SESSION["page"]["page_id"] ?>" />
	<button class="baseProfile" type="submit" id="picture" style="border:0; padding:5px;">
		<img id="profilPic" src="img_pages_groups/<?= $_SESSION["page"]["picture"] ?>" alt="" width="40px">
	</button>
	<button class="baseProfile" type="submit" id="first_name" style="border:0; padding:0;"> 
		Retour à <?= $_SESSION["page"]["name"] ?> 
	</button>
</form>
<h1><?= $h1 ?></h1>
<section>
    <img id="" src="img_page_group/<?= $picture ?>" alt="" width="40px">
    <form action="/edit_page_photo" id="edit_picture" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_picture" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_picture">Valider</button>
    </form>
    <img id="" src="img_baniere/<?= $banner ?>" alt="" width=" 200px">
    <form action="/edit_page_banner" id="edit_banniere" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_ban" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_banniere">Valider</button>
    </form>
    <div>
        <h3>Nom</h3>
        <form id="new_name" method="post" action="/new_page_name">
            <input type="text" name="modify_name" value="<?= $name ?>">
            <button class="modify" type="submit">modifier</button>
        </form>
        <h3>Description</h3>
        <form id="new_description" method="post" action="/new_page_description">
            <input type="text" name="modify_description" value="<?= $description ?>">
            <button class="modify" type="submit">modifier</button>
        </form>
    </div>
