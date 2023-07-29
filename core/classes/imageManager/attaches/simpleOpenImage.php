<div class="modal-body">
    <div class="d-flex justify-content-center">
        <img id="image_modal" width="100%" height="100%" alt="Photo">
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
</div>
<script>
    $(document).ready(function () {
        var modal_data = getModalData();
        $('#image_modal').attr('src', 'data:image/jpg;base64, '+modal_data.file);
    })
</script>
