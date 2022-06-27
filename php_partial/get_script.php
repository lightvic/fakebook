<!-- select right script by checking url -->
<?php if($uri === "/entrer.php") :?>
    <!-- script here -->
<?php elseif($uri === "/login") :?>
    <!-- script here -->
<?php elseif($uri === "/timeline") :?>
    <script src="script/timeline_script.js?<?php echo time(); ?>"></script>
<?php elseif($uri === "/profile") :?>
    <script src="script/profile_script.js?<?php echo time(); ?>"></script>
<?php elseif($uri === "/public_page") :?>
    <script src="script/public_page_script.js?<?php echo time(); ?>"></script>
<?php elseif($uri === "/group") :?>
    <script src="script/group_script.js?<?php echo time(); ?>"></script>
<?php elseif($uri === "/new_chat") :?>
    <script src="script/new_chat_script.js?<?php echo time(); ?>"></script>
<?php elseif($uri === "/chat") :?>
    <script src="script/chat_script.js?<?php echo time(); ?>"></script>
<?php endif; ?>