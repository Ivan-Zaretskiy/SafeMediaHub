<form id="check2PINsFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="modalPIN">PIN</label>
                <input type="text" class="form-control" id="modalPIN" name="PIN" placeholder="Enter your first PIN">
            </div>
            <div class="form-group">
                <label for="modalPIN2">PIN 2</label>
                <input type="text" class="form-control" id="modalPIN2" name="PIN2" placeholder="Enter your second PIN">
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
    $('#check2PINsFrom').on('submit',function (e) {
        e.preventDefault();
        var modal_data = getModalData();
        $.ajax({
            method: "POST",
            url: '/load.php?page=keysManager&action=check2Pins',
            data: {'PIN': $('#modalPIN').val(), 'PIN2': $('#modalPIN2').val(), 'data': modal_data },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.success === true){
                    switch (modal_data.func){
                        case 'openImage':
                            showModal('Image', '/load.php?page=imageManager&action=openImage', response);
                            break;
                        case 'openImageNewWindow':
                            closeModal();
                            var win = window.open();
                            var href = 'data:image/jpg;base64, ' +response.file;
                            win.document.write('<img src="' + href  + '"></img>');
                            break;
                        case 'editField':
                            showModal('Edit Field', '/load.php?page=keysManager&action=editField&ajax=true', response);
                            break;
                        case 'downloadImage':
                            var a = document.createElement("a");
                            a.href = response.img;
                            a.download = response.name;
                            a.click();
                            closeModal();
                            break;
                        case 'generateMyKey':
                            if (response.name) {
                                showAlert('Key successfully generated!<br>' +
                                    'Click <a onclick="clickEnvFile(\'' + response.name + '\')" style="color: #29b348!important;font-size: 1.1rem;" href="/temporary_user_files/' + response.name + '">here</a> to downolad key!', 'success');
                                showProfileButtons(true)
                                closeModal();
                            } else {
                                showAlert(response.error_message, 'error');
                                closeModal();
                            }
                            break;
                        case 'resetKey':
                            showAlert('Key reset successfully!', 'success');
                            updateAllTables();
                            showProfileButtons(false)
                            closeModal();
                    }
                } else {
                    showAlert('Wrong PIN', 'error');
                }
            }
        })
    })

    function clickEnvFile(name) {
        setTimeout(() => {
            $.ajax({
                method: "POST",
                url: '/load.php?page=keysManager&action=removeEnvFile',
                data: {'name': name}
            });
        }, 1000);
    }
</script>
