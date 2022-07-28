<form id="uploadKeyForm">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="key">File</label>
                    <input type="file" class="form-control" id="key" name="key" placeholder="Upload key">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#uploadKeyForm').on('submit',function (e){
        e.preventDefault();
        let data =  new FormData(this);
        let url = '/load.php?page=keysManager&action=uploadKey';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            cache:false,
            contentType: false,
            processData: false,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    if (imagesTable) imagesTable.ajax.reload();
                    if (fieldsTable) fieldsTable.ajax.reload();
                    showAlert('Key successfully uploaded', 'success');
                    closeModal();
                } else {
                    showAlert(response.error_message ?? 'Error on uploaded key', 'error');
                }
            }
        })
    })
</script>
