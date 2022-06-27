<div class="container col-md-4 d-grid gap-2  border border-primary rounded" style="margin-top : 5%; margin-bottom: 5%;">
    <form method="POST" id="form_validate">
        <label class="col-md-4 mt-3 mx-5 " for="name">Name :</label>
        <input class="col-md-8 mx-5" type="text" id="name" name="name" required>
        <input class="col-md-8 mx-5" type="hidden" name="friend" id="friend" required>
        <button class="col-md-4 mt-4 mx-5 bg-primary" type="submit" id="valider">Valider</button>
    </form>

    <form method="post" class="col-md-8 mx-5 mt-3">
        <select id="select">
            <!-- show every friends of the user -->
            <?php foreach ($friend_profils as $friend_profil): ?>
            <option value="<?= $friend_profil["user_id"] . " " . $friend_profil["first_name"] . " " . $friend_profil["last_name"]  ?>"><?= $friend_profil["first_name"] . " " . $friend_profil["last_name"] ?></option>
            <?php endforeach; ?>
        </select>
        <button class="col-md-10 mb-4 mt-2 bg-primary" type="button" id="add_friend">Ajouter un ami</button>
    </form>
</div>