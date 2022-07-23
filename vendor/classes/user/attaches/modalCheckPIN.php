<form id="checkPINFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="modalPIN">PIN</label>
                <input type="password" class="form-control" id="modalPIN" name="PIN" placeholder="Enter your first PIN">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#modalPIN').focus();
    $('#checkPINFrom').on('submit',function (e) {
        var id = <?=$id?>;
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: '/load.php?page=user&action=checkUserPINEncryptedString&id='+id,
            data: {'PIN': $('#modalPIN').val() },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.success === true){
                    if (response.decrypted_value) {
                        closeModal();
                        var str = '<span id="spanValue_' + id + '">' + response.decrypted_value + '</span> <button class="btn btn-warning" onclick="copyToClipboard(\'spanValue_' + id + '\')"><i class="fa fa-copy"></i</button>'
                        $('#encrypted_' + id).html(str);
                        showAlert('Field successfully loaded', 'success');
                    } else {
                        showAlert('Can\'t load this field', 'error');
                    }
                } else {
                    showAlert('Wrong PIN', 'error');
                }
            }
        })
    })
</script>
