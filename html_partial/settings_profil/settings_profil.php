<section>
    <img id="" src="img_profil/<?= $profil_picture ?>" alt="" width="40px">
    <form action="/edit_photo" id="edit_profil" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_picture" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_picture">Valider</button>
    </form>
    <img id="" src="img_baniere/<?= $banner ?>" alt="" width=" 200px">
    <form action="/edit_banniere" id="edit_banniere" method="post" enctype="multipart/form-data">
        <input type="file" name="upload_ban" accept="image/jpeg, image/png, image/gif, image/jpg">
        <button type="submit" id="valider_banniere">Valider</button>
    </form>
    <h2>Modifier profil</h2>
    <div>
        <h3>Prénom</h3>
        <form id="new_first_name" method="post" action="/new_first_name">
            <input type="text" name="modifi_fname" value="<?= $first_name ?>">
            <button class="modifi" type="submit">Modifier</button>
        </form>
        <h3>Nom</h3>
        <form id="new_last_name" method="post" action="/new_last_name">
            <input type="text" name="modifi_lname" value="<?= $last_name ?>">
            <button class="modifi" type="submit">Modifier</button>
        </form>
        <h3>Email</h3>
        <form id="new_email" method="post" action="/new_email">
            <input type="email" name="modifi_email" value="<?= $email ?>">
            <button class="modifi" type="submit">Modifier</button>
        </form>
        <h3>Mot de passe</h3>
        <form id="new_password" method="post" action="/new_password">
            <button class="modifi" type="submit">Modifier</button>
            <input type="password" name="password" placeholder="Mot de passe actuel">
            <input type="password" name="new_password" placeholder="Nouveau mot de passe">
            <input type="password" name="confirm_password" placeholder="Confirm mot de passe">
        </form>
    </div>
</section>
<form action="/delete" method="post">
    <input type="submit" id="disable" name="disable" value="désactiver">
    <input type="submit" id="delete" name="delete" value="supprimer">
</form>
<h2>Sélection du thème</h2>
<form method = "post" action = "/theme">
    <input class="form-check-input" type="hidden" name="choice" id="theme_input" value="<?=$theme;?>">
    <button class="btn btn-primary" type="submit">Changer theme</label>
</form>
