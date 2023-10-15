<div class="modal-body">
    <div style="margin: 20px">
        <div class="row">
            <div class="col-md-4">
                <?php if(!empty($series->image_url)) { ?>
                    <div class="d-flex justify-content-center">
                        <img src="<?=$series->image_url?>" alt="Main image" width="300" height="400">
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <p><strong>Name: </strong><?=$series->name?></p>
                </div>
                <div class="form-group">
                    <p><strong>Category: </strong><?=$series->category?></p>
                </div>
                <div class="form-group">
                    <span class="d-flex">
                        <strong class="m-r-10 m-t-5">Watch status:</strong>
                        <form id="changeWatchStatus" style="position: relative; bottom: 5px">
                            <select type="text" class="form-control h-100" id="watch_status_<?=$series->id;?>" name="watch_status_<?=$series->id;?>">
                                <?php foreach ($this->watchStatuses as $watch_status){ ?>
                                    <option value="<?=$watch_status->id?>" <?= $watch_status->id == $series->watch_status ? "selected" : ''?>><?=$watch_status->name?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </span>
                </div>
                <div class="form-group">
                    <p>
                        <strong>Last watched season: </strong>
                        <button onclick="seasonAction(<?=$series->id?>, '#season_info_', true)"><i class="fa fa-minus"></i></button>
                        <span id='season_info_<?=$series->id?>'><?=$series->last_season?></span>
                        <button onclick="seasonAction(<?=$series->id?>, '#season_info_', false)"><i class="fa fa-plus"></i></button>
                    </p>
                </div>
                <div class="form-group">
                    <p>
                        <strong>Last watched episode: </strong>
                        <button onclick="episodeAction(<?=$series->id?>, '#episode_info_', true)"><i class="fa fa-minus"></i></button>
                        <span id='episode_info_<?=$series->id?>'><?=$series->last_episode?></span>
                        <button onclick="episodeAction(<?=$series->id?>, '#episode_info_', false)"><i class="fa fa-plus"></i></button>
                        <button onclick="episodeAction(<?=$series->id?>, '#episode_info_', false, trueerial)"><i class="fa fa-refresh"></i></button>
                    </p>
                </div>
                <div class="form-group">
                    <p><strong>URL to watch: </strong><a href="<?=$series->url_to_watch?>" target="_blank"><?=$series->url_to_watch?></a></p>
                </div>
                <div class="form-group d-flex m-b-4">
                    <p>
                        <strong>Next episode date: </strong>
                        <form id="changeNextEpisodeDateForm_<?=$series->id;?>" style="position: relative; bottom: 5px">
                            <input class="w-75" id="next_episode_date_<?=$series->id;?>" type="text" value="<?=$series->next_episode_date?>" style="border: 1px solid;padding: 5px;font-size: 15px;border-radius: 7px;margin-left: 10px;">
                            <i class="fa fa-refresh" onclick="$('#next_episode_date_<?=$series->id;?>').val(''); $('#submit_next_episode_date_<?=$series->id;?>').click();"></i>
                            <button id="submit_next_episode_date_<?=$series->id;?>" type="submit"><i class="fa fa-check"></i></button>
                        </form>
                    </p>
                </div>
                <div class="form-group d-flex m-b-4">
                    <p>
                        <strong>Last watched episode time: </strong>
                        <form id="changeTimeForm_<?=$series->id;?>" style="position: relative; bottom: 5px">
                            <input class="w-75" id="time_<?=$series->id;?>" type="text" value="<?=$series->last_episode_time?>" style="border: 1px solid;padding: 5px;font-size: 15px;border-radius: 7px;margin-left: 10px;">
                            <i class="fa fa-refresh" onclick="$('#time_<?=$series->id;?>').val('00:00:00'); $('#submit_time_<?=$series->id;?>').click();"></i>
                            <button id="submit_time_<?=$series->id;?>" type="submit"><i class="fa fa-check"></i></button>
                        </form>
                    </p>
                </div>
                <div class="form-group d-flex m-b-4">
                    <strong>Additional info: </strong>
                    <form id="changeAdditionalInfoForm_<?=$series->id;?>" class="d-flex justify-content-center">
                        <textarea class="form-control" name="additional_info" id="additional_info_<?=$series->id;?>" onkeyup="textAreaAdjust(this)" style="margin-left: 10px; overflow: hidden"><?=$series->additional_info;?></textarea>
                        <button type="submit"><i class="fa fa-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <?php if (!empty($series->iframe_html)) { ?>
            <fieldset class="custom-fieldset">
                <legend class="custom-legend" align="right">
                    Player
                </legend>
                <div class="player" style="min-height: 600px;padding: 20px;">
                    <iframe src="<?=$series->iframe_html;?>" allowfullscreen="allowfullscreen" style="width:100%;min-height:inherit;"></iframe>
                </div>
            </fieldset>
        <?php } ?>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
</div>
<script>
    var id = '<?=$series->id?>';
    $(document).ready(function () {
        $('#next_episode_date_'+id).datepicker({
            container:'#myModal',
            format: "yyyy-mm-dd"
        });
        textAreaAdjust(document.getElementById('additional_info_'+id));
    })

    $('#changeTimeForm_'+id).on('submit', function (e) {
        e.preventDefault();
        blockCUI();
        var time = $('#time_'+id).val();
        $.ajax({
            method: "POST",
            url: '/load.php?page=series&action=changeTime',
            data: {'id': id, 'time': time},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    unblockCUI();
                    $('#last_episode_time_'+id)[0].innerText = time;
                    showAlert('Time successfully changed', 'success');
                } else {
                    unblockCUI();
                    showAlert(response.error_message ?? 'Try Later!', 'error');
                }
            }
        });
    });

    $('#changeNextEpisodeDateForm_'+id).on('submit', function (e) {
        e.preventDefault();
        blockCUI();
        var date = $('#next_episode_date_'+id).val();
        $.ajax({
            method: "POST",
            url: '/load.php?page=series&action=changeNextEpisodeDate',
            data: {'id': id, 'date': date},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    unblockCUI();
                    $('#next_episode_date_main_'+id)[0].innerText = date;
                    showAlert('Date successfully changed', 'success');
                } else {
                    unblockCUI();
                    showAlert(response.error_message ?? 'Try Later!', 'error');
                }
            }
        });
    });
    $('#watch_status_<?=$series->id;?>').on('change', function (e) {
        e.preventDefault();
        blockCUI();
        var status = $('#watch_status_'+id).val();
        $.ajax({
            method: "POST",
            url: '/load.php?page=series&action=changeWatchStatus',
            data: {'id': id, 'status': status},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    unblockCUI();
                    if (typeof ($('#watch_status_main_'+id)[0]) !== 'undefined') $('#watch_status_main_'+id)[0].innerText = response.new_value;
                    showAlert('Watch status successfully changed', 'success');
                } else {
                    unblockCUI();
                    showAlert(response.error_message ?? 'Try Later!', 'error');
                }
            }
        });
    })
    $('#changeAdditionalInfoForm_'+id).on('submit', function (e) {
        e.preventDefault();
        blockCUI();
        $.ajax({
            method: "POST",
            url: '/load.php?page=series&action=changeAdditionalInfo',
            data: {'id': id, 'additional_info': $('#additional_info_'+id).val()},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    unblockCUI();
                    showAlert('Additional Info successfully changed', 'success');
                } else {
                    unblockCUI();
                    showAlert(response.error_message ?? 'Try Later!', 'error');
                }
            }
        });
    });
</script>
