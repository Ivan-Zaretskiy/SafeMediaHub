<form id="check2PINsFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="modalPIN">PIN</label>
                <input type="password" class="form-control" id="modalPIN" name="PIN" placeholder="Enter your first PIN">
            </div>
            <div class="form-group">
                <label for="modalPIN2">PIN 2</label>
                <input type="password" class="form-control" id="modalPIN2" name="PIN2" placeholder="Enter your second PIN">
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
                    }
                } else {
                    showAlert('Wrong PIN', 'error');
                }
            }
        })
    })
</script>
