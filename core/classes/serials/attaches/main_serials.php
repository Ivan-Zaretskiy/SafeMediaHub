<div class="overflow-hidden">
     <div class="overflow-auto p-20-px">
         <h1 class="float-l">Serials</h1>
         <button class="btn btn-success float-r m-20-px" onclick="showModal('Add new serials','/load.php?page=serials&action=addNewSerial&ajax=true')">Add new serials</button>
         <table id="serialsTable" class="m-20-px w-100">
             <thead>
                 <tr>
                     <th>Name</th>
                     <th>Category</th>
                     <th>Watch Status</th>
                     <th>Season</th>
                     <th>Episode</th>
                     <th>Time</th>
                     <th>Date</th>
                     <th>Updated time</th>
                     <th>Option</th>
                 </tr>
             </thead>
             <tfoot style="display: table-row-group;">
                 <tr>
                     <td><input class='form-control m-input' type="text" name="name" id="name"></td>
                     <td>
                         <select class='form-control m-input' name="category" id="category">
                             <option value="-1"></option>
                             <?php foreach($this->serial_categories as $category){?>
                                 <option value="<?=$category?>"><?=$category?></option><?php
                             }?>
                         </select>
                     </td>
                     <td>
                         <select class='form-control m-input' name="full_watch_status" id="full_watch_status">
                             <option value="-1"></option>
                             <?php foreach($this->watch_statuses as $status){?>
                                 <option value="<?=$status->id?>"><?=$status->name?></option><?php
                             }?>
                         </select>
                     </td>
                     <td><input class='form-control m-input' name="last_season" id="last_season"></td>
                     <td><input class='form-control m-input' type="text" name="last_episode" id="last_episode"></td>
                     <td><input class='form-control m-input' type="text" name="last_episode_time" id="last_episode_time"></td>
                     <td><input class='form-control m-input' type="text" name="next_episode_date" id="next_episode_date"></td>
                     <td><input class='form-control m-input' type="text" name="updated_at" id="updated_at"></td>
                     <td></td>
                 </tr>
             </tfoot>
             <tbody></tbody>
         </table>
     </div>
</div>
<script>
    var timeout;
    var dom = '<"row"<"col-md-6"l><"col-md-6">>B'+
        '<"dataTables_scroll dataTables_wrapper"<"table-dataTables_scrollBody table-adaptive"t>>'+
        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';
    let serialsTable = $('#serialsTable').DataTable({
        dom:dom,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/load.php?page=serials&action=getSerials',
            method: "POST",
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
        pageLength: 50,
        order: [[0,'asc']],
        columns: [
            { title: 'Name', data: 'name', name: 's.name', sortable: true, render: function (data, type, row) {
                var url = '';
                if (row.url_to_watch) url = '<a href="'+row.url_to_watch+'" target="_blank" title="Link to view"><i class="fa fa-link" style="margin-left: 5px;   color: #007bff!important"></a>';
                return data + url;
            }},
            { title: 'Category', data: 'category', name: 'category', sortable: true, render: function (data) {
                return data ?? '';
            }},
            { title: 'Watch Status', data: 'full_watch_status', name: 'watch_status', sortable: true, render: function (data, type, row) {
                    var val = data ??  '';
                    return '<span id="watch_status_main_'+row.id+'">'+val+'</span>';
            }},
            { title: 'Season', data: 'last_season', name: 'last_season', sortable: true, render: function (data, type, row) {
                var minus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="seasonAction('+row.id+', \'#season_\', false)"><i class="fa fa-plus"></i></button>';
                var value = data ?? '';

                return minus_button + " <span id='season_"+row.id+"'>"+value+"</span> " + plus_button;
            }},
            { title: 'Episode', data: 'last_episode', name: 'last_episode', sortable: true, render: function (data, type, row) {
                var minus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', true)"><i class="fa fa-minus"></i></button>';
                var plus_button = '<button onclick="episodeAction('+row.id+', \'#episode_\', false)"><i class="fa fa-plus"></i></button>';
                var first_button = '  <button onclick="episodeAction('+row.id+', \'#episode_\', false, true)"><i class="fa fa-refresh"></i></button>';
                var value = data ?? '';

                return minus_button + " <span id='episode_"+row.id+"'>"+value+"</span> " + plus_button + first_button;
            }},
            { title: 'Time', data: 'last_episode_time', name: 'last_episode_time', sortable: true, render: function (data, type, row) {
                return data ? '<span id="last_episode_time_'+row.id+'">'+data+'</span>': '';
            }},
            { title: 'Date', data: 'next_episode_date', name: 'next_episode_date', sortable: true, render: function (data, type, row) {
                var val = data ? data : ''
                return '<span id="next_episode_date_main_'+row.id+'">'+val+'</span>';
            }},
            { title: 'Updated time', data: 'updated_at', name: 's.updated_at', sortable: true, render: function (data){
                return data ?? '';
            }},
            { title: "Option", data: "id", sortable: false, render: function (data){
                let str = '<div class="button-group">';
                str += '<button class="btn btn-warning button-group-item button-radius" title="Serial Info" onclick="showModal(\'Serial Info\', \'/load.php?page=serials&action=info&id='+data+'&ajax=true\')"><i class="fa fa-eye"></i></button>';
                str += '<button class="btn btn-primary button-group-item button-radius" title="Edit Serial" onclick="showModal(\'Edit Serial\', \'/load.php?page=serials&action=editSerial&id='+data+'&ajax=true\')"><i class="fa fa-pencil-square-o"></i></button>';
                str +='<button class="btn btn-danger button-group-item button-radius" title="Delete Serial" onclick="checkPin(\'deleteSerial\', ' + data + ')"><i class="fa fa-trash"></i></button>';
                str += '</div>';

                return str;
            }}
        ],
        initComplete: function () {
            $('#serialsTable>tfoot input, #serialsTable>tfoot select').on('keyup change',function () {
                clearTimeout(timeout);
                var idx = $(this).parent('td').index();
                serialsTable.column(idx).search($(this).val());
                timeout = setTimeout(function () {
                    serialsTable.draw();
                }, 300);
            });
        }
    } );
</script>
