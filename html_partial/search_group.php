</section>
<?php
$memory=[];
$general_memory=[];
foreach ($groups as $group){
    $tampon = $who;
    while(strlen($who)){

// improve the search 
        $name_group = strtolower($group["name"]);
        $group_id =$group["group_id"];
        array_push($general_memory,$name_group);
        if (strlen($who) > 0){
            if(in_array($name_group,$memory) == false){
            array_push($memory,$name_group);?>
            <form id="goToProfile" action="/group" method="post">
                <input type="hidden" name="group_id" value="<?= $group["group_id"]; ?>" />
                <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                    <img id="profilPic" src="img_pages_groups/<?=  $group["picture"] ?>" alt="" width="40px">
                </button>
                <button type="submit" id="group_id" class="baseProfile" style="border:0; padding:0;"> 
                    <?= $group["name"]?>     
                </button>
            </form>
            <?php 
            $who = $tampon;
            break;
        }
        $who=substr($who,0,-1);
        }
    };
}

// find any groups which don't where before
foreach ($names as $name):
    $page_id = $name["group_id"];
    $name_group = strtolower($name["name"]);
    if (in_array($name_group,$memory) == false){
        ?>
        <form id="goToProfile" action="/group" method="post">
        <input type="hidden" name="group_id" value="<?= $name["group_id"]; ?>" />
        <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                    <img id="profilPic" src="img_pages_groups/<?=  $name["picture"] ?>" alt="" width="40px">
                </button>
            <button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
                <?= $name["name"]?>     
            </button>
        </form>
    
    <?php }
        endforeach;?>