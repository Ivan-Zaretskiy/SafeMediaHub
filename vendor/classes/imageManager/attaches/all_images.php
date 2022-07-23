<div class="p-20-px">
    <h1 class="float-l">Images</h1>
    <button class="btn btn-success float-r m-20-px" title="Load new image by URL" onclick="showModal('Load new image by URL','/load.php?page=imageManager&action=loadNewImage&ajax=true')">Load URL</button>
    <button class="btn btn-info float-r m-20-px"  title="Load new image by File" onclick="showModal('Load new image by File','/load.php?page=imageManager&action=loadNewImageFile&ajax=true')">Load <i class="fa fa-file"></i></button>
    <table id="imagesTable" class="m-20-px"></table>
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
                    str += '<button class="btn btn-warning button-group-item button-radius" title="Open Image" onclick="showModal(\'Open Image\', \'/load.php?page=imageManager&action=openImage&id='+data+'&ajax=true\')"><i class="fa fa-eye"></i></button>';
                    str += '<button class="btn btn-info button-group-item button-radius" title="Open Image In New Window" onclick="openPhotoNewWindow('+data+');"><i class="fa fa-external-link"></i></button>';
                    str +='<button class="btn btn-danger button-group-item button-radius" title="Delete Image" onclick="deleteImage(' + data + ')"><i class="fa fa-trash"></i></button>';
                    str += '</div>';

                    return str;
                }}
        ]
    } );

    function deleteImage(id) {
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
                        let url_delete_image = '/load.php?page=imageManager&action=deleteImage';
                        $.ajax({
                            method: "POST",
                            url: url_delete_image,
                            data: {'id': id},
                            success: function (data) {
                                let response = JSON.parse(data);
                                if (response.success === true) {
                                    imagesTable.ajax.reload();
                                    showAlert('Image successfully deleted', 'success');
                                } else {
                                    showAlert(response.error_message ?? 'Can\'t deleted this image', 'error');
                                }
                            }
                        });
                    }
                }
            }
        )
    }

    function openPhotoNewWindow(id) {
        var url_data = '/load.php?page=imageManager&action=getDecryptedHref';
        $.ajax({
            method: "POST",
            url: url_data,
            data: {'id': id},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    var win = window.open();
                    var href = 'data:image/jpg;base64, ' +response.decrypt;
                    win.document.write('<img src="' + href  + '"></img>');
                } else {
                    showAlert(response.error_message ?? 'Can\'t deleted this image', 'error');
                }
            }
        });
    }
</script>
