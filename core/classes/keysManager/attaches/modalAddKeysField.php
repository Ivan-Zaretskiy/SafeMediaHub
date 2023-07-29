<form id="addCustomFieldFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="form-group">
                <label for="fieldName">Field Name</label>
                <input type="text" class="form-control" id="fieldName" name="fieldName" placeholder="Name of your field">
            </div>
            <div class="form-group">
                <label for="fieldValue">Field Value</label>
                <input type="text" class="form-control" id="fieldValue" name="fieldValue" placeholder="Value of your field">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#addCustomFieldFrom').on('submit',function (e){
        e.preventDefault();

        let form = $('#addCustomFieldFrom');
        let data =  form.serialize();
        let url = '/load.php?page=keysManager&action=addCustomField';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    fieldsTable.ajax.reload();
                    showAlert('Successfully added field: <strong>' + response.name + '</strong>', 'success');
                    closeModal();
                } else {
                    showAlert('Error on added new field', 'error');
                }
            }
        })
    })
</script>
