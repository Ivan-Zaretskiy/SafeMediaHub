<?php include_once ('templates/html/head.php'); ?>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="/img/login.png" alt="IMG">
            </div>
            <?php if (isset($type) && $type == 'sign_up') { ?>
            <form id="sign_up_form" class="login100-form validate-form" method="post" action="/index.php?page=login&action=createNewAccount">
                <span class="login100-form-title">
                    Create Account
                </span>
                <?php if ($error) { ?>
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong>Danger!</strong> <?= $error ?>
                </div>
                <?php } ?>
                <div class="wrap-input100 validate-input" data-validate="Username is required">
                    <input class="input100" type="text" name="username" placeholder="Username">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email" placeholder="Email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="First PIN is required">
                    <input id="firstPIN" class="input100" type="text" name="firstPIN" minlength="4" maxlength="4" placeholder="First PIN (4 symbols)">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Second PIN is required">
                    <input id="secondPIN" class="input100" type="text" name="secondPIN" minlength="8" maxlength="8" placeholder="Second PIN (8 symbols)">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input id="password" class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Passwords don't match">
                    <input id="confirm_password" class="input100" type="password" name="confirm_password" placeholder="Confirm password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="container-login100-form-btn">
                    <button id="submit_login" class="login100-form-btn">
                        Sign Up
                    </button>
                </div>
                <div class="text-center p-t-12">
                    <a class="txt2" href="/">
                        Sign in (if you have account)
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>
            </form>
            <?php } else { ?>
            <form id="login_form" class="login100-form validate-form" method="post">
                <span class="login100-form-title">
                    Member Login
                </span>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email" placeholder="Email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="container-login100-form-btn">
                    <button id="submit_login" class="login100-form-btn">
                        Login
                    </button>
                </div>
                <div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
                    <a class="txt2" href="#">
                        Username / Password?
                    </a>
                </div>
                <div class="text-center p-t-12">
                    <a class="txt2" href="/index.php?page=login&action=sign_up">
                        Create your Account
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function (){
        let input = $('.validate-input .input100');

        $('.validate-form').on('submit',function(){
            var check = true;

            for(let i=0; i<input.length; i++) {
                if(validate(input[i]) == false){
                    showValidate(input[i]);
                    check=false;
                }
            }

            return check;
        });


        $('.validate-form .input100').each(function(){
            $(this).focus(function(){
                hideValidate(this);
            });
        });

        function validate (input) {
            if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                    return false;
                }
            }
            else if ($(input).attr('name') == 'password') {
                if($(input).val().trim() == ''){
                    return false;
                }
            }
            else if ($(input).attr('name') == 'firstPIN') {
                if($(input).val().trim() == ''){
                    return false;
                }
            }
            else if ($(input).attr('name') == 'secondPIN') {
                if($(input).val().trim() == ''){
                    return false;
                }
            }
            else if ($(input).attr('name') == 'confirm_password') {
                if($(input).val().trim() != $('#password').val().trim() || $(input).val().trim() == ''){
                    return false;
                }
            } else if($(input).attr('name') == 'username'){
                if ($(input).val().trim() == ''){
                    return false
                }
            }
        }

        function showValidate(input) {
            let thisAlert = $(input).parent();

            $(thisAlert).addClass('alert-validate');
        }

        function hideValidate(input) {
            let thisAlert = $(input).parent();

            $(thisAlert).removeClass('alert-validate');
        }

        $('.js-tilt').tilt({
            scale: 1.1
        })
    });
</script>
