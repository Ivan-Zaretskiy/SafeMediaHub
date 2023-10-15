<form id="newImage">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name of image">
                </div>
                <div class="form-group col-md-6">
                    <label for="url_image">URL</label>
                    <input type="text" class="form-control" id="url_image" name="url_image" placeholder="URL to image">
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
    $('#newImage').on('submit',function (e){
        e.preventDefault();

        let form = $('#newImage');
        let data =  form.serialize();
        let url = '/load.php?page=images&action=loadNewImage';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    imagesTable.ajax.reload();
                    showAlert('Image successfully added', 'success');
                    closeModal();
                } else {
                    showAlert('Error on added new image', 'error');
                }
            }
        })
    })
</script>
