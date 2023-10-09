<a class="btn btn-<?=$user->getNextMode();?> button-radius m-r-10" title="Switch mode to: <?=$user->getNextMode();?>" style="font-size: 1.3em;" href="/index.php?page=homepage&action=switchMode">
    <i class="fa fa-<?=$user->getModeIcon();?>"></i>
</a>
