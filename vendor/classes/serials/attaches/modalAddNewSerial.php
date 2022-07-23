<form id="addNewSerialFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name of serial">
                </div>
                <div class="form-group col-md-6">
                    <label for="category">Category</label>
                    <select type="text" class="form-control" id="category" name="category">
                        <?php foreach ($this->serial_categories as $category){ ?>
                            <option value="<?=$category?>"><?=$category?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="last_season">Last watched season</label>
                    <input type="text" class="form-control" id="last_season" name="last_season" placeholder="Number of last watched season">
                </div>
                <div class="form-group col-md-6">
                    <label for="last_episode">Last watched episode</label>
                    <input type="text" class="form-control" id="last_episode" name="last_episode" placeholder="Number of last watched episode">
                </div>
                <div class="form-group col-md-6">
                    <label for="url_to_watch">URL</label>
                    <input type="text" class="form-control" id="url_to_watch" name="url_to_watch" placeholder="URL to watch">
                </div>
                <div class="form-group col-md-6">
                    <label for="image_url">Image</label>
                    <input type="text" class="form-control" id="image_url" name="image_url" placeholder="URL to image">
                </div>
                <div class="form-group col-md-6">
                    <label for="iframe_html">Iframe</label>
                    <input type="text" class="form-control" id="iframe_html" name="iframe_html" placeholder="Iframe for video">
                </div>
                <div class="form-group col-md-6">
                    <label for="last_episode_time">Last watched episode time</label>
                    <input type="text" class="form-control" id="last_episode_time" name="last_episode_time" placeholder="Time of last watched episode">
                </div>
                <div class="form-group col-md-6">
                    <label for="next_episode_date">Next episode date</label>
                    <input type="text" class="form-control" id="next_episode_date" name="next_episode_date" placeholder="Date of next episode">
                </div>
                <div class="form-group col-md-6">
                    <label for="is_planned">You only planning watch this serial?</label>
                    <input type="checkbox" class="m-l-15" style="width:20px; height:20px;" id="is_planned" name="is_planned">
                </div>
                <div class="form-group col-md-6">
                    <label for="is_finished">You finished watch this serial?</label>
                    <input type="checkbox" class="m-l-15" style="width:20px; height:20px;" id="is_finished" name="is_finished">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
    </div>
</form>
<script>
    $('#addNewSerialFrom').on('submit',function (e){
        e.preventDefault();

        let form = $('#addNewSerialFrom');
        let data =  form.serialize();
        let url = '/load.php?page=serials&action=addNewSerial';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    serialsTable.ajax.reload();
                    showAlert('Successfully added serial: <strong>' + response.name + '</strong>', 'success');
                    closeModal();
                } else {
                    showAlert('Error on added new serial', 'error');
                }
            }
        })
    })

    $('#next_episode_date').datepicker({
        dateFormat: "yy-mm-dd"
    });
</script>
