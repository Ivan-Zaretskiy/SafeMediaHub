<div class="container-pass">
    <div class="main-pass">
        <div class="topbar-pass">
            <div class="toggle-pass" onclick="toggleMenuAdmin()"></div>
            <div class="buttons-header">
                <?php include_once('templates/theme_switcher.php');?>
                <a class="btn btn-info" href="index.php?page=login&action=logout">Logout</a>
            </div>
        </div>
        <div class="load-content">
            <div class="page-content" id="page-1" data-ready="false"></div>
            <div class="page-content" id="page-2" data-ready="false"></div>
            <div class="page-content" id="page-3" data-ready="false"></div>
            <div class="page-content" id="page-4" data-ready="false"></div>
        </div>
    </div>
    <div class="navigation-pass">
        <ul class="ul-pass">
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-apple" aria-hidden="true"></i></span>
                    <span class="title-pass"><h5 class="h2-main">Protection Manager</h5></span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="profile" page-id="page-1" ajax-href="/load.php?page=user&action=profile" ready="false">Profile</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="loadMain" page-id="page-2" ajax-href="/load.php?page=homepage&action=loadMain" ready="false">Encrypted fields</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-tv" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="serials" page-id="page-3" ajax-href="/load.php?page=serials&action=loadSerials" ready="false">Serials</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-image" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="images" page-id="page-4" ajax-href="/load.php?page=imageManager&action=loadAllImages" ready="false">Images</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function (){
        var need_page = localStorage.getItem('last_page') ?? 0;
        if (+window.localStorage.getItem("preferHidden")) {
            toggleMenuAdmin(false);
        }
        $('.load-title-pass').each(function (index,value) {
            if (index === parseInt(need_page)){
                load_page(index, value);
            }
            $(value).closest('.li-pass').click( function(){
                load_page(index, value);
            })
        });
    })
</script>
