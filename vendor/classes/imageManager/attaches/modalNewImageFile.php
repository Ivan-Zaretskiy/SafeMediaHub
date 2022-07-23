<form id="newImageFile">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name of image">
                </div>
                <div class="form-group col-md-6">
                    <label for="url_image">File</label>
                    <input type="file" class="form-control" id="file" name="file" placeholder="Upload image">
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
    $('#newImageFile').on('submit',function (e){
        e.preventDefault();
        let data =  new FormData(this);
        let url = '/load.php?page=imageManager&action=loadNewImageFile';

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
                    imagesTable.ajax.reload();
                    showAlert('Image successfully added', 'success');
                    closeModal();
                } else {
                    showAlert(response.error_message ?? 'Error on added new image', 'error');
                }
            }
        })
    })
</script>
