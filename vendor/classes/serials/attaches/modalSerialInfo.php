<div class="modal-body">
    <div style="margin: 20px">
        <div class="row">
            <div class="col-md-4">
                <?php if(!empty($serial['image_url'])) { ?>
                    <div class="d-flex justify-content-center">
                        <img src="<?=$serial['image_url']?>" alt="Main image" width="300" height="400">
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <p><strong>Name: </strong><?=$serial['name']?></p>
                </div>
                <div class="form-group">
                    <p><strong>Category: </strong><?=$serial['category']?></p>
                </div>
                <div class="form-group">
                    <p>
                        <strong>Last watched season: </strong>
                        <button onclick="seasonAction(<?=$serial['id']?>, '#season_info_', true)"><i class="fa fa-minus"></i></button>
                        <span id='season_info_<?=$serial['id']?>'><?=$serial['last_season']?></span>
                        <button onclick="seasonAction(<?=$serial['id']?>, '#season_info_', false)"><i class="fa fa-plus"></i></button>
                    </p>
                </div>
                <div class="form-group">
                    <p>
                        <strong>Last watched episode: </strong>
                        <button onclick="episodeAction(<?=$serial['id']?>, '#episode_info_', true)"><i class="fa fa-minus"></i></button>
                        <span id='episode_info_<?=$serial['id']?>'><?=$serial['last_episode']?></span>
                        <button onclick="episodeAction(<?=$serial['id']?>, '#episode_info_', false)"><i class="fa fa-plus"></i></button>
                        <button onclick="episodeAction(<?=$serial['id']?>, '#episode_info_', false, true)"><i class="fa fa-refresh"></i></button>
                    </p>
                </div>
                <div class="form-group d-flex m-b-4">
                    <p>
                        <strong>Last watched episode time: </strong>
                        <form id="changeTime" style="position: relative; bottom: 5px">
                            <input id="time_<?=$serial['id'];?>" type="text" value="<?=$serial['last_episode_time']?>" style="border: 1px solid;padding: 5px;font-size: 15px;border-radius: 7px;margin-left: 10px;">
                            <i class="fa fa-refresh" onclick="$('#time_<?=$serial['id'];?>').val('00:00:00'); $('#submit_time_<?=$serial['id'];?>').click();"></i>
                            <button id="submit_time_<?=$serial['id'];?>" type="submit"><i class="fa fa-check"></i></button>
                        </form>
                    </p>
                </div>
                <div class="form-group d-flex m-b-4">
                    <p>
                        <strong>Next episode date: </strong>
                        <form id="changeNextEpisodeDate" style="position: relative; bottom: 5px">
                            <input id="next_episode_date_<?=$serial['id'];?>" type="text" value="<?=$serial['next_episode_date']?>" style="border: 1px solid;padding: 5px;font-size: 15px;border-radius: 7px;margin-left: 10px;">
                            <i class="fa fa-refresh" onclick="$('#next_episode_date_<?=$serial['id'];?>').val(''); $('#submit_next_episode_date_<?=$serial['id'];?>').click();"></i>
                            <button id="submit_next_episode_date_<?=$serial['id'];?>" type="submit"><i class="fa fa-check"></i></button>
                        </form>
                    </p>
                </div>
                <div class="form-group">
                    <p><strong>URL to watch: </strong><a href="<?=$serial['url_to_watch']?>" target="_blank"><?=$serial['url_to_watch']?></a></p>
                </div>
                <div class="form-group">
                    <p><strong>You only planning watch this serial?: </strong><?=$serial['is_planned'] == 1 ? 'Yes' : 'No'?></p>
                </div>
                <div class="form-group">
                    <p><strong>You finished watch this serial?: </strong><?=$serial['is_finished'] == 1 ? 'Yes' : 'No'?></p>
                </div>
            </div>
        </div>
        <?php if (!empty($serial['iframe_html'])) { ?>
            <div class="player" style="min-height: 600px;padding: 20px;">
                <iframe src="<?=$serial['iframe_html'];?>" allowfullscreen="allowfullscreen" style="width:100%;min-height:inherit;"></iframe>
            </div>
        <?php } ?>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-danger" style="color: white" onclick="closeModal()">Cancel</a>
</div>
<script>
    var id = '<?=$serial['id']?>';
    $('#next_episode_date_'+id).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('#changeTime').on('submit', function (e) {
        e.preventDefault();
        blockCUI();
        var time = $('#time_'+id).val();
        $.ajax({
            method: "POST",
            url: '/load.php?page=serials&action=changeTime',
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

    $('#changeNextEpisodeDate').on('submit', function (e) {
        e.preventDefault();
        blockCUI();
        var date = $('#next_episode_date_'+id).val();
        $.ajax({
            method: "POST",
            url: '/load.php?page=serials&action=changeNextEpisodeDate',
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
</script>
