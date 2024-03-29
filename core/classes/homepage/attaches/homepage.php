<div class="container-pass">
    <div class="main-pass">
        <div class="topbar-pass">
            <div class="toggle-pass" onclick="toggleMenuAdmin()"></div>
            <div class="buttons-header">
                <?php include_once('templates/html/theme_switcher.php');?>
                <a class="btn btn-info" href="/index.php?page=login&action=logout">Logout</a>
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
                    <span class="icon-pass"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <span class="title-pass"><h5 class="h2-main">SafeMediaHub</h5></span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="profile" page-id="page-1" ajax-href="/index.php?page=settings&action=main&appMode=load" ready="false">Profile Settings</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-sticky-note" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="loadMain" page-id="page-2" ajax-href="/index.php?page=notes&action=main&appMode=load" ready="false">Notes</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-tv" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="series" page-id="page-3" ajax-href="/index.php?page=series&action=main&appMode=load" ready="false">Series</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-image" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="images" page-id="page-4" ajax-href="/index.php?page=images&action=main&appMode=load" ready="false">Images</span>
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
