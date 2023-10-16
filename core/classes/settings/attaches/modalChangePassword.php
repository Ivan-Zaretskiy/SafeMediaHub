<form id="changePasswordFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="fieldName">Current password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter your current password">
            </div>
            <div class="form-group">
                <label for="newPassword">New password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your new password">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#changePasswordFrom').on('submit',function (e){
        e.preventDefault();
        if ($('#confirmPassword').val() !== '' && $('#newPassword').val() !== '' && $('#currentPassword').val() !== '') {
            if ($('#confirmPassword').val() === $('#newPassword').val()) {
                let form = $('#changePasswordFrom');
                let data = form.serialize();
                let url = '/index.php?page=settings&action=changePassword&appMode=load';

                $.ajax({
                    method: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        let response = JSON.parse(data);
                        if (response.success === true) {
                            showAlert('Password successfully changed', 'success');
                            closeModal();
                        } else {
                            showAlert(response.text, 'error');
                        }
                    }
                })
            } else {
                showAlert('New passwords doesn\'t match');
            }
        } else {
            showAlert('Enter all inputs', 'error');
        }
    })
</script>
