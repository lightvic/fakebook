<button><a style="text-decoration: none; color: black;" href="/conversation">Discussions</a></button>
<section id="message">
    <h1><?= $_SESSION["chat"]["name"] ?></h1>
    <button type="button" id="change_chat_img_btn" style="background: white; border:0; padding:5px;">
        <img id="profilPic" src="img_chat_profil/<?=$_SESSION["chat"]["chat_pic"]?>" alt="" width="50px">
    </button>
    <form id="change_chat_img" action="change_chat_img" method="post" enctype="multipart/form-data">
        <div id="depose">Déposez vos images ou cliquez pour choisir</div>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg, image/png, image/gif, image/jpg">
        <div id="preview"></div>
        <button>Valider</button>
        <button type="button" id="cancel">Annuler</button>
    </form>
    <form action="/quit_chat" method="post">
        <input type="hidden" name="quit_chat_id" value="<?=$chat_id?>">
        <button>Quitter le chat</button>
    </form>
    <section id="section_message" style="overflow: scroll; border: 1px solid black; padding: 10px; height: 200px; width: 900px;">
        
    </section>
    <form action="/new_message" method="post" id="new_message_form" style="margin: 30px;">
        <textarea rows="2" cols="50"  name="new_message" id="new_message" required></textarea><br>
        <input id="chat_id" type="hidden" name="chat_id" value="<?= $chat_id ?>">
        <button id="new_message_btn" >Envoyer</button>
    </form>
</section>