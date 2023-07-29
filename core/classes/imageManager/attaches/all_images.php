<div class="overflow-hidden">
    <div class="overflow-auto p-20-px">
        <h1 class="float-l">Images</h1>
        <button class="btn btn-success float-r m-20-px m-l-0 button-radius" title="Load new image by URL" onclick="checkPin('loadNewImage')">URL</button>
        <button class="btn btn-info float-r m-20-px m-r-10 button-radius"  title="Load new image by File" onclick="checkPin('loadNewImageFile')"><i class="fa fa-file"></i></button>
        <table id="imagesTable" class="m-20-px w-100"></table>
    </div>
</div>
<script>
    var imagesTable = $('#imagesTable').DataTable({
        ajax: {
            url: '/load.php?page=imageManager&action=getImages',
            method: "GET",
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
        pageLength: 25,
        columns: [
            { title: 'ID',data: 'id', render: function (data) {
                return data ?? '';
            }},
            { title: 'Name',data: 'name', render: function (data) {
                return data ?? '';
            }},
            { title: 'Updated time', data: 'updated_at', render: function (data){
                return data ?? '';
            }},
            { title: "Option", data: "id", render: function (data){
                let str = '<div class="button-group">';
                str += '<button class="btn btn-warning button-group-item button-radius" title="Open Image" onclick="check2Pins(\'openImage\', '+data+')"><i class="fa fa-eye"></i></button>';
                str += '<button class="btn btn-info button-group-item button-radius" title="Open Image In New Window" onclick="check2Pins(\'openImageNewWindow\', '+data+')"><i class="fa fa-external-link"></i></button>';
                str +='<button class="btn btn-success button-group-item button-radius" title="Download Image" onclick="check2Pins(\'downloadImage\',' + data + ')"><i class="fa fa-download"></i></button>';
                str +='<button class="btn btn-danger button-group-item button-radius" title="Delete Image" onclick="checkPin(\'deleteImage\',' + data + ')"><i class="fa fa-trash"></i></button>';
                str += '</div>';

                return str;
            }}
        ]
    } );
</script>
