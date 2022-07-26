<?php
$next_mode = $_SESSION['loginUser']['dark_mode'] == 0 ? 'dark' : 'light';
$icon = $_SESSION['loginUser']['dark_mode'] == 0 ? 'moon-o' : 'sun-o'; ?>
<a class="btn btn-<?=$next_mode?> button-radius m-r-10" title="Switch mode to: <?=$next_mode?>" style="font-size: 1.3em;" href="index.php?page=homepage&action=switchMode">
    <i class="fa fa-<?=$icon?>"></i>
</a>
