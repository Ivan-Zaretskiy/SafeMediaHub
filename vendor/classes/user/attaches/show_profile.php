<div class="p-20-px">
    <div class="d-flex justify-content-sm-between">
        <h1 class="float-l">Profile</h1>
        <button class="btn btn-success float-r m-20-px" onclick="showModal('Change password','/load.php?page=user&action=changePassword&ajax=true')">Change password</button>
    </div>
    <form id="changeProfileForm">
        <div class="row gutters p-20-px">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="profileUsername">Full Name</label>
                    <input type="text" class="form-control" id="profileUsername" name="username" value="<?= $this->user['username']; ?>" placeholder="Enter your username">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="profileEmail">Email</label>
                    <input type="email" class="form-control" id="profileEmail" name="email" value="<?= $this->user['email']; ?>" placeholder="Enter your email">
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
    $('#changeProfileForm').on('submit',function (e) {
        e.preventDefault();

        var form = $('#changeProfileForm');
        var data = form.serialize();
        var url = '/load.php?page=user&action=editProfile';

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
