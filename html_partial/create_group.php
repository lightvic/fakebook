<div id="newgroup">
    <!-- Form new group-->
    <form id="newgroupForm" method="post" action="/new_group">
        <label id="groupLabel" for="groupInput">Donnez un nom à votre groupe</label><br>
        <input id="groupInput" name="groupInput" type="text"></input><br>
		<label id="descriptionLabel" for="descriptionInput">Décrivez votre groupe</label><br>
        <textarea id="descriptionInput" name="descriptionInput" type="text"></textarea><br>
		<label id="privacyLabel" for="privacyInput">Choisissez la confidentialité de votre groupe :</label><br>
        <input id="public" name="privacyInput" type="radio" value="public">Public</input>
        <input id="private" name="privacyInput" type="radio" value="private">Privé</input><br>
        <button type="submit" id="submitPublication">Valider</button>
    </form>
</div>
