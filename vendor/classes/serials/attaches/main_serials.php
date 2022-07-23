<div class="p-20-px">
    <h1 class="float-l">Serials</h1>
    <button class="btn btn-success float-r m-20-px" onclick="showModal('Add new serials','/load.php?page=serials&action=addNewSerial&ajax=true')">Add new serials</button>
    <table id="serialsTable" class="m-20-px"></table>
</div>
<script>
    let is_yes = '<button class="btn btn-success"><i class="fa fa-check"></i></button>';
    let is_not = '<button class="btn btn-danger"><i class="fa fa-close"></i></button>';
    var serialsTable = $('#serialsTable').DataTable({
        ajax: {
            url: '/load.php?page=serials&action=getSerials',
            method: "GET",
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
        pageLength: 25,
        columns: [
            { title: 'Name',data: 'name', render: function (data) {
                return data ?? '';
            }},
            { title: 'Category',data: 'category', render: function (data) {
                return data ?? '';
            }},
            { title: 'Last season', data: 'last_season', render: function (data, type, row) {
                var minus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', false)"><i class="fa fa-plus"></i></button>';

                return data ? minus_button + " <span id='season_"+row.id+"'>"+data+"</span> " + plus_button : '-';
            }},
            { title: 'Last episode', data: 'last_episode', render: function (data, type, row) {
                var minus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', false)"><i class="fa fa-plus"></i></button>';
                var first_button = '  <button onclick="episodeAction('+row.id+', \'#episode_\', false, true)"><i class="fa fa-refresh"></i></button>';

                return data ? minus_button + " <span id='episode_"+row.id+"'>"+data+"</span> " + plus_button + first_button : '-';
            }},
            { title: 'Last watched episode time', data: 'last_episode_time', render: function (data, type, row) {
                return data ? '<span id="last_episode_time_'+row.id+'">'+data+'</span>': '';
            }},
            { title: 'Next episode date', data: 'next_episode_date', render: function (data, type, row) {
                return data ? '<span id="next_episode_date_main_'+row.id+'">'+data+'</span>': '';
            }},
            { title: 'URL to watch', data: 'url_to_watch', render: function (data) {
                return data ? '<a href="'+data+'" target="_blank">URL</a>' : '';
            }},
            { title: 'Is Planned', data: 'is_planned', render: function (data) {
                return data == 1 ? is_yes : is_not;
            }},
            { title: 'Is Finished', data: 'is_finished', render: function (data) {
                return data == 1 ? is_yes : is_not;
            }},
            { title: 'Updated time', data: 'updated_at', render: function (data){
                return data ?? '';
            }},
            { title: "Option", data: "id", render: function (data){
                let str = '<div class="button-group">';
                str += '<button class="btn btn-warning button-group-item" onclick="showModal(\'Serial Info\', \'/load.php?page=serials&action=info&id='+data+'&ajax=true\')">Serial Info</button>';
                str += '<button class="btn btn-primary button-group-item" onclick="showModal(\'Edit Serial\', \'/load.php?page=serials&action=editSerial&id='+data+'&ajax=true\')">Edit Serial</button>';
                str +='<button class="btn btn-danger button-group-item" onclick="deleteSerial(' + data + ')">Delete Serial</button>';
                str += '</div>';

                return str;
            }}
        ]
    } );

    function deleteSerial(id) {
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
                        let url_delete_serial = '/load.php?page=serials&action=deleteSerial';
                        $.ajax({
                            method: "POST",
                            url: url_delete_serial,
                            data: {'id': id},
                            success: function (data) {
                                let response = JSON.parse(data);
                                if (response.success === true) {
                                    serialsTable.ajax.reload();
                                    showAlert('Serial successfully deleted', 'success');
                                } else {
                                    showAlert('Can\'t deleted this serial', 'error');
                                }
                            }
                        });
                    }
                }
            }
        )
    }
</script>
