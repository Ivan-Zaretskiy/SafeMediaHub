<div class="p-20-px">
    <h1 class="float-l">Encrypted fields</h1>
    <button class="btn btn-success float-r m-20-px" onclick="showModal('Add Field','/load.php?page=keysManager&action=addCustomField&ajax=true')">Add New Filed</button>
    <table id="fieldsTable" class="m-20-px"></table>
</div>
<script>
    var fieldsTable = $('#fieldsTable').DataTable({
        ajax: {
            url: '/load.php?page=keysManager&action=getFields',
            method: "GET",
        },
        columns: [
            { title: 'Field name', data: 'name' },
            { title: 'Encrypted text',data: 'encryptedText', render: function (data,type,row){
                return '<span id="encrypted_'+row.id+'"><button class="btn btn-warning" onclick="checkPINEncryptedString('+row.id+')">Show decrypted</button></span>';
            }},
            { title: 'Created time', data: 'created_at' },
            { title: 'Updated time', data: 'updated_at', render: function (data){
                return data ?? '';
            }},
            { title: "Option", data: "id", render: function (data){
                let str = '<div class="button-group">'
                str += '<button class="btn btn-primary button-group-item" onclick="showModal(\'Edit Field\', \'/load.php?page=keysManager&action=editField&id='+data+'&ajax=true\')">Edit field</button>';
                str +='<button class="btn btn-danger button-group-item" onclick="deleteField(' + data + ')">Delete field</button>';
                str += '</div>';
                return str;
            }}
        ]
    } );

    function deleteField(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
                if (result.isConfirmed) {
                    if (id !== '') {
                        let url_delete = '/load.php?page=keysManager&action=deleteField';
                        $.ajax({
                            method: "POST",
                            url: url_delete,
                            data: {'id': id},
                            success: function (data) {
                                let response = JSON.parse(data);
                                if (response.success === true) {
                                    fieldsTable.ajax.reload();
                                    showAlert('Field successfully deleted', 'success');
                                } else {
                                    showAlert('Can\'t deleted this field', 'error');
                                }
                            }
                        });
                    }
                }
            }
        )
    }
</script>
