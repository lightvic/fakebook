</section>
<?php
$memory=[];
$general_memory=[];
foreach ($pages as $page){
    $tampon = $who;
    while(strlen($who)){

// improve the search 
        $name_page = strtolower($page["name"]);
        $page_id =$page["page_id"];
        array_push($general_memory,$name_page);
        if (strlen($who) > 0){
            if(in_array($name_page,$memory) == false){
            array_push($memory,$name_page);?>
            <form id="goToProfile" action="/public_page" method="post">
                <input type="hidden" name="page_id" value="<?= $page["page_id"]; ?>" />
                <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                    <img id="profilPic" src="img_pages_groups/<?=  $page["picture"] ?>" alt="" width="40px">
                </button>
                <button type="submit" id="page_id" class="baseProfile" style="border:0; padding:0;"> 
                    <?= $page["name"]?>     
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

// find any pages which don't where before
foreach ($names as $name):
    $page_id = $name["page_id"];
    $name_page = strtolower($name["name"]);
    if (in_array($name_page,$memory) == false){
        ?>
        <form id="goToProfile" action="/public_page" method="post">
        <input type="hidden" name="page_id" value="<?= $name["page_id"]; ?>" />
        <button type="submit" id="profil_picture" class="baseProfile" style="border:0; padding:5px;">
                    <img id="profilPic" src="img_pages_groups/<?=  $name["picture"] ?>" alt="" width="40px">
                </button>
            <button type="submit" id="first_name" class="baseProfile" style="border:0; padding:0;"> 
                <?= $name["name"]?>     
            </button>
        </form>
    
    <?php }
        endforeach;?>