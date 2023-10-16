<div class="p-20-px">
    <div class="d-flex justify-content-sm-between">
        <h1 class="float-l">Profile</h1>
        <div class="buttons" id="profile_buttons"></div>
    </div>
    <form id="changeProfileForm">
        <div class="row gutters p-20-px">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="profileUsername">Full Name</label>
                    <input type="text" class="form-control" id="profileUsername" name="username" value="<?= SessionUser::get('username'); ?>" placeholder="Enter your username">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="profileEmail">Email</label>
                    <input type="email" class="form-control" id="profileEmail" name="email" value="<?= SessionUser::get('email'); ?>" placeholder="Enter your email">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="firstPIN">First PIN (4 characters)</label>
                    <input type="text" class="form-control" id="firstPIN" name="firstPIN" value="<?= $firstPIN; ?>" placeholder="Create your first PIN">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="secondPIN">Second PIN (8 characters)</label>
                    <input type="text" class="form-control" id="secondPIN" name="secondPIN" value="<?= $secondPIN; ?>" placeholder="Create your second PIN">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-warning">Update Profile</button>
        </div>
    </form>
</div>
<script>
    let userHaveKey = Boolean(<?= SessionUser::haveKey(); ?>);

    $(document).ready(function () {
        showProfileButtons(userHaveKey);
    });

    function showProfileButtons(have_key) {
        var buttons = '';
        var uploadKeyButton = `<button class="btn btn-primary m-20-px" onclick="showModal('Upload Key', '/index.php?page=notes&action=uploadKey&ajax=true&appMode=load');">Upload Key</button>`;
        var changePasswordButton = `<button class="btn btn-success float-r m-20-px" onclick="showModal('Change password','/index.php?page=settings&action=changePassword&ajax=true&appMode=load')">Change password</button>`;
        var resetKeyButton= `<button class="btn btn-danger float-r m-20-px" onclick="check2Pins('resetKey')">Reset Key</button>`;
        var generateKeyButton= `<button class="btn btn-info float-r m-20-px" onclick="check2Pins('generateMyKey')">${have_key ? 'Regenerate Key' : 'Generate Key'}</button>`;
        if (have_key) {
            buttons = uploadKeyButton + changePasswordButton + resetKeyButton + generateKeyButton;
        } else {
            buttons =  changePasswordButton + generateKeyButton;
        }
        $('#profile_buttons').html(buttons);
    }

    $('#changeProfileForm').on('submit',function (e) {
        e.preventDefault();

        var form = $('#changeProfileForm');
        var data = form.serialize();
        var url = '/index.php?page=settings&action=editProfile&appMode=load';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data) {
                var response = JSON.parse(data);
                if (response.success === true) {
                    showAlert('Profile info successfully changed', 'success');
                } else {
                    showAlert(response.text, 'error');
                }
            }
        })
    });
</script>
