<?php
foreach($profiles as $profile):
    foreach($friends as $friend):
        if($friend["status"]== "pending" && $friend["user_id_b"] == $profile["user_id"]){
            $id = $friend["user_id_a"];
?>
<!-- friend request -->
        <form id="friend" action="/profile" method="post">
        <input type="hidden" name="profil_id" value="<?= $id ?>" />
            <button type="submit" id="profil_picture" style="background: white; border:0; padding:5px;">
                <img src="img_profil/<?=  $_SESSION["user"]["profil_picture"] ?>" alt="" width="40px">
            </button>
            <button type="hidden" id="first_name" style="background: white; border:0; padding:0;"> Nouvelle demande de relation </button>
        </form>
        <?php
                        
        }
    endforeach;
endforeach;
foreach($notifications as $notification):
    if ($notification['type'] == 'like' && $notification['seen'] == 'no'){
        ?>
<!-- new comment -->
        <form action="/timeline" method='post'>
            <input type="hidden" value="<?= $user_id ?>">
            <button type="hidden" id="first_name" style="background: white; border:0; padding:0;"> Nouveau like </button>
        </form>
    <?php
    }
    if ($notification['type'] == 'comment' && $notification['seen'] == 'no'){
        ?>
<!-- new like -->
        <form action="/timeline" method='post'>
            <input type="hidden" value="<?= $user_id ?>">
            <button type="hidden" id="first_name" style="background: white; border:0; padding:0;"> Nouveau commentaire </button>
        </form>
    <?php
    }

endforeach;