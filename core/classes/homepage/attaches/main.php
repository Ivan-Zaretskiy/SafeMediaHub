<div class="overflow-hidden">
    <div class="overflow-auto p-20-px">
        <h1 class="float-l">Notes</h1>
        <button class="btn btn-success float-r m-20-px button-radius" title="Add New Field" onclick="checkPin('addField')"><i class="fa fa-plus"></i></button>
        <table id="fieldsTable" class="m-20-px w-100"></table>
    </div>
</div>
<script>
    var fieldsTable = $('#fieldsTable').DataTable({
        ajax: {
            url: '/load.php?page=notes&action=getFields',
            method: "GET",
        },
        columns: [
            { title: 'Field name', data: 'name' },
            { title: 'Field Value', data: 'value', render: function (data,type,row){
                return '<span id="encrypted_'+row.id+'"><button class="btn btn-warning" onclick="checkPin(\'showNotes\', '+row.id+')">Show decrypted</button></span>';
            }},
            { title: 'Created time', data: 'created_at' },
            { title: "Option", data: "id", render: function (data){
                let str = '<div class="button-group">'
                str += '<button class="btn btn-primary button-group-item button-radius" title="Edit Field" onclick="check2Pins(\'editField\', '+data+')"><i class="fa fa-pencil-square-o"></i></button>';
                str +='<button class="btn btn-danger button-group-item button-radius" title="Delete Field" onclick="checkPin(\'deleteField\', ' + data + ')"><i class="fa fa-trash"></i></button>';
                str += '</div>';
                return str;
            }}
        ]
    } );
</script>
