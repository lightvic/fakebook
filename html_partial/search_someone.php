</section>
<?php
$memory=[];
$general_memory=[];
foreach ($profiles as $profile){
    // improve the search with the first name
    $tampon_first = $who_first_name;
    $tampon_last = $who_last_name;
    while(strlen($who_first_name) > 0 || strlen($who_last_name)>0){
        $first_name = strtolower($profile["first_name"]);
        $last_name = strtolower($profile["last_name"]);
        $user_id =$profile["user_id"];
        array_push($general_memory,$user_id);
        if (strlen($who_first_name) > 0){
            if(in_array($user_id,$memory) == false){
            array_push($memory,$user_id);?>
            <form id="goToProfile" action="/profile" method="post">
            <input type="hidden" name="profil_id" value="<?= $profile["user_id"] ?>" />
                <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                    <img id="profilPic" src="img_profil/<?=  $profile["profil_picture"] ?>" alt="" width="40px">
                </button>
                <button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
					<?= $profile["first_name"] . " " . $profile["last_name"]?>    
                </button>
            </form>
            <?php 
            $who_first_name = $tampon_first;
            $who_last_name = $tampon_last;
            break;
        }
        $who_first_name=substr($who_first_name,0,-1);
    }
// improve the search with the first name
        if(strlen($who_last_name)>0){
            if(in_array($user_id,$memory) == false){
                array_push($memory,$user_id);?>
            <form id="goToProfile" action="/profile" method="post">
            <input type="hidden" name="profil_id" value="<?= $profile["user_id"] ?>" />
                <button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
                    <?= $first_name . " " . $last_name?>     
                </button>
            </form>
        
        <?php
                $who_first_name = $tampon_first;
                $who_last_name = $tampon_last;
                break; 
            }
            $who_last_name=substr($who_last_name,0,-1);
        }
    };
    $who_first_name = $tampon_first;
    $who_last_name = $tampon_last;
}

// find anyone which don't where before
foreach ($names as $name):
    $user_id = $name["user_id"];
    $first_name = strtolower($name["first_name"]);
    $last_name = strtolower($name["last_name"]);
    if (in_array($user_id,$memory) == false){
        ?>
        <form id="goToProfile" action="/profile" method="post">
        <input type="hidden" name="profil_id" value="<?= $name["user_id"] ?>" />
            <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                <img id="profilPic" src="img_profil/<?=  $name["profil_picture"] ?>" alt="" width="40px">
            </button>
            <button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
                <?= $name["first_name"] . " " . $name["last_name"]?>     
            </button>
        </form>
    
    <?php }
        endforeach;?>