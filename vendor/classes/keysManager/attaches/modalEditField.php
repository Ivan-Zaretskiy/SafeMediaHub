<form id="editFieldFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="fieldName">Field Name</label>
                <input type="text" class="form-control" id="fieldName" name="fieldName" placeholder="Name of your field" value="<?=$fieldName;?>">
            </div>
            <div class="form-group">
                <label for="fieldValue">Field Value</label>
                <input type="text" class="form-control" id="fieldValue" name="fieldValue" placeholder="Value of your field" value="<?=$fieldValue;?>">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#editFieldFrom').on('submit',function (e){
        e.preventDefault();

        let form = $('#editFieldFrom');
        let data =  form.serialize();
        let url = '/load.php?page=keysManager&action=editField&id=<?=$id?>';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    fieldsTable.ajax.reload();
                    showAlert('Successfully edited field: <strong>' + response.name + '</strong>', 'success');
                    closeModal();
                } else {
                    showAlert('Error on edited new field', 'error');
                }
            }
        })
    })
</script>
