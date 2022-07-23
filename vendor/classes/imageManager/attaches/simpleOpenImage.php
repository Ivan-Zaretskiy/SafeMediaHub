<div class="modal-body">
    <div class="d-flex justify-content-center">
        <img src='data:image/jpg;base64, <?=base64_encode($image['decrypt'])?>' width="100%" height="100%">
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success">Submit</button>
    <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
</div>
