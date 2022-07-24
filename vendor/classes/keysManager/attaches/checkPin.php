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
        e.preventDefault();
        var modal_data = getModalData();
        $.ajax({
            method: "POST",
            url: '/load.php?page=keysManager&action=checkPin',
            data: {'PIN': $('#modalPIN').val(), 'data': modal_data },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.success === true){
                    switch (modal_data.func){
                        case 'deleteImage':
                            closeModal();
                            showAlert('Image successfully deleted', 'success');
                            imagesTable.ajax.reload()
                            break;
                        case 'showEncryptedString':
                            closeModal();
                            var str = '<span id="spanValue_' + modal_data.id + '">' + response.decrypted_value + '</span> <button class="btn btn-warning" onclick="copyToClipboard(\'spanValue_' + modal_data.id + '\')"><i class="fa fa-copy"></i</button>'
                            $('#encrypted_' + modal_data.id).html(str);
                            break;
                        case 'deleteField':
                            closeModal();
                            fieldsTable.ajax.reload();
                            showAlert('Field successfully deleted', 'success');
                            break;
                        case 'addField':
                            showModal('Add Field','/load.php?page=keysManager&action=addCustomField&ajax=true');
                            break;
                        case 'deleteSerial':
                            closeModal();
                            serialsTable.ajax.reload();
                            showAlert('Serial successfully deleted', 'success');
                            break;
                        case 'loadNewImage':
                            showModal('Load new image by URL','/load.php?page=imageManager&action=loadNewImage&ajax=true');
                            break;
                        case 'loadNewImageFile':
                            showModal('Load new image by URL','/load.php?page=imageManager&action=loadNewImageFile&ajax=true');
                            break;
                    }
                } else {
                    showAlert('Wrong PIN', 'error');
                }
            }
        })
    })
</script>
