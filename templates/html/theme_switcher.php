<a class="btn btn-<?=SessionUser::getNextMode();?> button-radius m-r-10" title="Switch mode to: <?=SessionUser::getNextMode();?>" style="font-size: 1.3em;" href="/index.php?page=settings&action=switchMode">
    <i class="fa fa-<?=SessionUser::getModeIcon();?>"></i>
</a>
