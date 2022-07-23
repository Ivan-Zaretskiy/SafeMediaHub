<div class="container-pass">
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
                    <span class="icon-pass"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="loadMain" page-id="page-1" ajax-href="/load.php?page=homepage&action=loadMain" ready="false">Encrypted fields</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="profile" page-id="page-2" ajax-href="/load.php?page=user&action=profile" ready="false">Profile</span>
                </a>
            </li>
            <li class="li-pass">
                <a href="javascript:void(0)">
                    <span class="icon-pass"><i class="fa fa-tv" aria-hidden="true"></i></span>
                    <span class="title-pass load-title-pass" name="serials" page-id="page-3" ajax-href="/load.php?page=serials&action=loadSerials" ready="false">Serials</span>
                </a>
            </li>
<!--            <li class="li-pass">-->
<!--                <a href="javascript:void(0)">-->
<!--                    <span class="icon-pass"><i class="fa fa-question-circle" aria-hidden="true"></i></span>-->
<!--                    <span class="title-pass load-title-pass" name="help" page-id="page-4" ajax-href="/admin/adminHelp" ready="false">Help</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="li-pass">-->
<!--                <a href="javascript:void(0)">-->
<!--                    <span class="icon-pass"><i class="fa fa-cog" aria-hidden="true"></i></span>-->
<!--                    <span class="title-pass load-title-pass" name="settings" page-id="page-5" ajax-href="/admin/adminSettings" ready="false">Settings</span>-->
<!--                </a>-->
<!--            </li>-->
        </ul>
    </div>
    <div class="main-pass">
        <div class="topbar-pass">
            <div class="toggle-pass" onclick="toggleMenuAdmin()"></div>
            <div class="search-pass">
                <label>
                    <input type="text" placeholder="Search here">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </label>
            </div>
            <div class="user-pass">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8cmFuZG9tJTIwcGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&w=1000&q=80" alt="User Photo">
            </div>
        </div>
        <div class="load-content">
            <div class="page-content" id="page-1" data-ready="false"></div>
            <div class="page-content" id="page-2" data-ready="false"></div>
            <div class="page-content" id="page-3" data-ready="false"></div>
<!--            <div class="page-content" id="page-4" data-ready="false"></div>-->
<!--            <div class="page-content" id="page-5" data-ready="false"></div>-->
        </div>
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
    function load_page(index, elem){
        var span_page_id = $(elem).attr('page-id');
        var span_href = $(elem).attr('ajax-href');
        var page = $('#'+span_page_id);
        localStorage.setItem('last_page', index);
        $('.li-pass').removeClass('active-pass');
        $(elem).closest('.li-pass').addClass('active-pass');
        $('.page-content').removeClass('active-page');
        page.addClass('active-page');
        if (page.attr('data-ready') === 'false'){
            page.load(span_href);
        }
        page.attr('data-ready','true');
    }
    function toggleMenuAdmin(with_change = true) {
        var toggleAdmin = document.querySelector('.toggle-pass');
        var navigationAdmin = document.querySelector('.navigation-pass');
        var mainAdmin = document.querySelector('.main-pass');
        if (with_change) {
            if (+window.localStorage.getItem("preferHidden")) {
                window.localStorage.setItem("preferHidden", 0);
            } else {
                window.localStorage.setItem("preferHidden", 1);
            }
        }
        toggleAdmin.classList.toggle('active');
        navigationAdmin.classList.toggle('active');
        mainAdmin.classList.toggle('active');
    }
</script>
