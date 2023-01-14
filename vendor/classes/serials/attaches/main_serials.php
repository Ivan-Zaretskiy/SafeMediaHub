<div class="overflow-hidden">
     <div class="overflow-auto p-20-px">
         <h1 class="float-l">Serials</h1>
         <button class="btn btn-success float-r m-20-px" onclick="showModal('Add new serials','/load.php?page=serials&action=addNewSerial&ajax=true')">Add new serials</button>
         <table id="serialsTable" class="m-20-px w-100"></table>
     </div>
</div>
<script>
    let is_yes = '<button class="btn btn-success button-radius"><i class="fa fa-check"></i></button>';
    let is_not = '<button class="btn btn-danger button-radius"><i class="fa fa-close"></i></button>';
    let serialsTable = $('#serialsTable').DataTable({
        ajax: {
            url: '/load.php?page=serials&action=getSerials',
            method: "GET",
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
        pageLength: 50,
        columns: [
            { title: 'Name',data: 'name', render: function (data) {
                return data ?? '';
            }},
            { title: 'Category',data: 'category', render: function (data) {
                return data ?? '';
            }},
            { title: 'Watch Status', data: 'full_watch_status', render: function (data, type, row) {
                    var val = data ??  '';
                    return '<span id="watch_status_main_'+row.id+'">'+val+'</span>';
            }},
            { title: 'Season', data: 'last_season', render: function (data, type, row) {
                var minus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', false)"><i class="fa fa-plus"></i></button>';
                var value = data ?? '';

                return minus_button + " <span id='season_"+row.id+"'>"+value+"</span> " + plus_button;
            }},
            { title: 'Episode', data: 'last_episode', render: function (data, type, row) {
                var minus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', false)"><i class="fa fa-plus"></i></button>';
                var first_button = '  <button onclick="episodeAction('+row.id+', \'#episode_\', false, true)"><i class="fa fa-refresh"></i></button>';
                var value = data ?? '';

                return minus_button + " <span id='episode_"+row.id+"'>"+value+"</span> " + plus_button + first_button;
            }},
            { title: 'Time', data: 'last_episode_time', render: function (data, type, row) {
                return data ? '<span id="last_episode_time_'+row.id+'">'+data+'</span>': '';
            }},
            { title: 'Date', data: 'next_episode_date', render: function (data, type, row) {
                var val = data ? data : ''
                return '<span id="next_episode_date_main_'+row.id+'">'+val+'</span>';
            }},
            { title: 'URL', data: 'url_to_watch', render: function (data) {
                return data ? '<a href="'+data+'" target="_blank">URL</a>' : '';
            }},
            { title: 'Updated time', data: 'updated_at', render: function (data){
                return data ?? '';
            }},
            { title: "Option", data: "id", render: function (data){
                let str = '<div class="button-group">';
                str += '<button class="btn btn-warning button-group-item button-radius" title="Serial Info" onclick="showModal(\'Serial Info\', \'/load.php?page=serials&action=info&id='+data+'&ajax=true\')"><i class="fa fa-eye"></i></button>';
                str += '<button class="btn btn-primary button-group-item button-radius" title="Edit Serial" onclick="showModal(\'Edit Serial\', \'/load.php?page=serials&action=editSerial&id='+data+'&ajax=true\')"><i class="fa fa-pencil-square-o"></i></button>';
                str +='<button class="btn btn-danger button-group-item button-radius" title="Delete Serial" onclick="checkPin(\'deleteSerial\', ' + data + ')"><i class="fa fa-trash"></i></button>';
                str += '</div>';

                return str;
            }}
        ]
    } );
</script>
