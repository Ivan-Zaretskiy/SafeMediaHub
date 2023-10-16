<form id="addNewSeriesFrom">
    <div class="modal-body">
        <div style="margin: 20px">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name of series">
                </div>
                <div class="form-group col-md-6">
                    <label for="url_to_watch">URL</label>
                    <input type="text" class="form-control" id="url_to_watch" name="url_to_watch" placeholder="URL to watch">
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
                    <label for="category">Category</label>
                    <select type="text" class="form-control" id="category" name="category">
                        <?php foreach ($this->seriesCategories as $category){ ?>
                            <option value="<?=$category?>"><?=$category?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="category">Watch status</label>
                    <select type="text" class="form-control" id="watch_status" name="watch_status">
                        <?php foreach ($this->watchStatuses as $watch_status){ ?>
                            <option value="<?=$watch_status->id?>"><?=$watch_status->name?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="iframe_html">Iframe</label>
                    <input type="text" class="form-control" id="iframe_html" name="iframe_html" placeholder="Iframe for video">
                </div>
                <div class="form-group col-md-6">
                    <label for="image_url">Image</label>
                    <input type="text" class="form-control" id="image_url" name="image_url" placeholder="URL to image">
                </div>
                <div class="form-group col-md-6">
                    <label for="next_episode_date">Next episode date</label>
                    <input type="text" class="form-control" id="next_episode_date" name="next_episode_date" placeholder="Date of next episode">
                </div>
                <div class="form-group col-md-6">
                    <label for="last_episode_time">Last watched episode time</label>
                    <input type="text" class="form-control" id="last_episode_time" name="last_episode_time" placeholder="Time of last watched episode">
                </div>
                <div class="form-group col-md-12">
                    <textarea class="form-control" id="additional_info" name="additional_info" placeholder="Additional Info" style="height: 125px"></textarea>
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
    $('#addNewSeriesFrom').on('submit',function (e){
        e.preventDefault();

        let form = $('#addNewSeriesFrom');
        let data =  form.serialize();
        let url = '/index.php?page=series&action=addNewSeries&appMode=load';

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function (data){
                let response = JSON.parse(data);
                if (response.success === true){
                    seriesTable.ajax.reload();
                    showAlert('Successfully added series: <strong>' + response.name + '</strong>', 'success');
                    closeModal();
                } else {
                    showAlert('Error on added new series', 'error');
                }
            }
        })
    })

    $('#next_episode_date').datepicker({
        container:'#myModal',
        format: "yyyy-mm-dd"
    });
</script>
