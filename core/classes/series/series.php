<?php
class series extends seriesHelper {

    public array $seriesCategories;
    public array $watchStatuses;

    public function __construct()
    {
        $this->watchStatuses = self::getWatchStatuses();
        $this->seriesCategories = self::getSeriesCategories();
    }

    private static function insertInDB(CustomObject $data): int
    {
        return query("
        INSERT INTO
            series
        SET
            user_id = :user_id,
            name = :seriesName,
            category = :category,
            last_season = :last_season,
            last_episode = :last_episode,
            image_url = :image_url,
            iframe_html = :iframe_html,
            last_episode_time = :last_episode_time,
            next_episode_date = :next_episode_date,
            additional_info = :additional_info,
            watch_status = :watch_status,
            url_to_watch = :url_to_watch
        ", [
            ':user_id' => $data->get('user_id'),
            ':seriesName' => $data->get('name'),
            ':category' => $data->get('category'),
            ':last_season' => $data->get('last_season'),
            ':last_episode' => $data->get('last_episode'),
            ':url_to_watch' => $data->get('url_to_watch'),
            ':image_url' => $data->get('image_url'),
            ':iframe_html' => $data->get('iframe_html'),
            ':last_episode_time' => $data->get('last_episode_time'),
            ':next_episode_date' => $data->get('next_episode_date'),
            ':additional_info' => $data->get('additional_info'),
            ':watch_status' => $data->get('watch_status'),
        ])->execute();
    }

    private static function updateInDB(CustomObject $data): int
    {
        return query("
        UPDATE
            series
        SET
            name = :seriesName,
            category = :category,
            last_season = :last_season,
            last_episode = :last_episode,
            image_url = :image_url,
            iframe_html = :iframe_html,
            last_episode_time = :last_episode_time,
            next_episode_date = :next_episode_date,
            additional_info = :additional_info,
            watch_status = :watch_status,
            url_to_watch = :url_to_watch
        WHERE
            id = :id
            ", [
            ':id' => $data->get('id'),
            ':seriesName' => $data->get('name'),
            ':category' => $data->get('category'),
            ':last_season' => $data->get('last_season'),
            ':last_episode' => $data->get('last_episode'),
            ':url_to_watch' => $data->get('url_to_watch'),
            ':image_url' => $data->get('image_url'),
            ':iframe_html' => $data->get('iframe_html'),
            ':last_episode_time' => $data->get('last_episode_time'),
            ':next_episode_date' => $data->get('next_episode_date'),
            ':additional_info' => $data->get('additional_info'),
            ':watch_status' => $data->get('watch_status'),
        ])->execute();
    }


    public function loadSeries()
    {
        include_once('attaches/main_series.php');
    }

    /**
     * @throws Exception
     */
    public function getSeries($check_user = true)
    {
        $q_select = '
        SELECT
            SQL_CALC_FOUND_ROWS s.*,
            ws.name AS full_watch_status
        FROM
            series AS s 

            LEFT JOIN WatchStatuses AS ws
            ON s.watch_status = ws.id';

        $q_where = "";
        $pre = " WHERE ";
        $q_limit = "";
        $orderBy = "";
        $orderByPre = " ORDER BY ";
        $params = [];
        if ($check_user) {
            $q_where = $pre . " s.user_id = :user_id";
            $params[':user_id'] = SessionUser::getUserID();
            $pre = " AND ";
        }
        if( sizeof($_POST['columns']) > 0 ) {
            foreach ($_POST['columns'] as $column) {
                if ($column['searchable'] == "true") {
                    if ($column['search']['value'] != "" && $column['search']['value'] != '-1') {
                        if ($column['name'] === 'next_episode_date') {
                            $q_where .= $pre . "DATE_FORMAT({$column['name']}, '%d.%m.%Y')" . " LIKE :{$column['data']}";
                        } elseif ($column['name'] === 's.updated_at') {
                            $q_where .= $pre . "DATE_FORMAT({$column['name']}, '%d.%m.%Y %H:%i:%s')" . " LIKE :{$column['data']}";
                        } else {
                            $q_where .= $pre . $column['name'] . " LIKE :{$column['data']}";
                        }
                        $params[':' . $column['data']] = "%" . $column['search']['value'] . "%";
                        $pre = " AND ";
                    }
                }
            }
        }

        if(isset($_POST['length']) && (int) $_POST['length'] != -1) {
            $q_limit = " LIMIT " . (int) $_POST['start'] . "," . (int) $_POST['length'];
        }

        foreach($_POST['order'] as $order){
            $orderBy .= $orderByPre.$_POST['columns'][$order['column']]['name']." ".$order['dir'];
            $orderByPre = ",";
        }
        $q = $q_select.$q_where.$orderBy.$q_limit;
        $result = query($q, $params)->fetchAll();
        $total = (int) query("SELECT FOUND_ROWS()")->fetchCell();

        foreach ($result as $item) {
            $item->next_episode_date = CustomDate::createFormatData(DATE_DATABASE_FORMAT, $item->next_episode_date, DATE_INTERFACE_FORMAT);
            $item->updated_at = CustomDate::createFormatData(DATETIME_DATABASE_FORMAT, $item->updated_at, DATETIME_INTERFACE_FORMAT);
            $item->last_episode_time = CustomDate::createFormatData(TIME_FORMAT, $item->last_episode_time, TIME_FORMAT);
        }

        echo json_encode(["draw"=>(int) $_POST['draw'], "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $result, "q" => $q]);
    }

    public function addNewSeries()
    {
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $data = new CustomObject();
            $data->set('user_id', SessionUser::getUserID());
            $data->set('name', $_POST['name']);
            $data->set('category', $_POST['category']);
            $data->set('last_season', (int) $_POST['last_season']);
            $data->set('last_episode', (int) $_POST['last_episode']);
            $data->set('url_to_watch', $_POST['url_to_watch']);
            $data->set('image_url', $_POST['image_url']);
            $data->set('iframe_html', $_POST['iframe_html']);
            $last_episode_time = $_POST['last_episode_time'] ? date_format(date_create($_POST['last_episode_time']), 'H:i:s') : '00:00:00';
            $data->set('last_episode_time', $last_episode_time);
            $next_episode_date = $_POST['next_episode_date'] ? date_format(date_create($_POST['next_episode_date']), 'Y-m-d') : null;
            $data->set('next_episode_date', $next_episode_date);
            $data->set('additional_info', $_POST['additional_info']);
            $data->set('watch_status', (int) $_POST['watch_status']);

            $ajax['success'] = false;
            if (!empty($data->name) && !empty($data->category)) {
                $result = self::insertInDB($data);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $data->name;
                } else {
                    $ajax['error_message'] = 'Error on insert!';
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalAddNewSeries.php');
        }
    }

    public function editSeries()
    {
        $id = (int) $_GET['id'];
        if (!empty($id)) {
            if (!isset($_GET['ajax'])) {
                $ajax = [];
                $data = new CustomObject();
                $data->set('id', $id);
                $data->set('name', $_POST['name']);
                $data->set('category', $_POST['category']);
                $data->set('last_season', (int) $_POST['last_season']);
                $data->set('last_episode', (int) $_POST['last_episode']);
                $data->set('url_to_watch', $_POST['url_to_watch']);
                $data->set('image_url', $_POST['image_url']);
                $data->set('iframe_html', $_POST['iframe_html']);
                $last_episode_time = $_POST['last_episode_time'] ? date_format(date_create($_POST['last_episode_time']), 'H:i:s') : '00:00:00';
                $data->set('last_episode_time', $last_episode_time);
                $next_episode_date = $_POST['next_episode_date'] ? date_format(date_create($_POST['next_episode_date']), 'Y-m-d') : null;
                $data->set('next_episode_date', $next_episode_date);
                $data->set('additional_info', $_POST['additional_info']);
                $data->set('watch_status', (int) $_POST['watch_status']);
                $ajax['success'] = false;
                if (!empty($data->name) && !empty($data->category)) {
                    self::updateInDB($data);
                    $ajax['success'] = true;
                    $ajax['name'] = $data->name;
                }
                echo json_encode($ajax);
            } else {
                $series = query('SELECT * FROM series WHERE id = ?', $id)->fetchRow();

                include_once('attaches/modalEditSeries.php');
            }
        }
    }

    public function seasonAction()
    {
        $ajax['success'] = false;
        $series = query("SELECT * FROM series WHERE id = ?", (int) $_POST['id'])->fetchRow();
        if ($series) {
            $season = (int) $_POST['season'];
            query("UPDATE series SET last_season = :season WHERE id = :id", [':season' => $_POST['season'], ':id' => $series->id])->execute();
            $ajax['success'] = true;
            $ajax['new_value'] = $season;
        } else {
            $ajax['error_message'] = 'Series Not Found!';
        }
        echo json_encode($ajax);
    }

    public function episodeAction()
    {
        $ajax['success'] = false;
        $series = query("SELECT * FROM series WHERE id = ?", (int) $_POST['id'])->fetchRow();
        if ($series) {
            $episode = (int) $_POST['episode'];
            query("UPDATE series SET last_episode = :episode WHERE id = :id", [':episode' => $episode, ':id' => $series->id])->execute();
            $ajax['success'] = true;
            $ajax['new_value'] = $episode;
        } else {
            $ajax['error_message'] = 'Series Not Found!';
        }
        echo json_encode($ajax);
    }

    public function info()
    {
        $id = (int) $_GET['id'];
        if ($id) {
            if ($_GET['ajax']) {
                $series = query("
                SELECT
                    s.*,
                    ws.name AS full_watch_status
                FROM
                    series AS s 

                    LEFT JOIN WatchStatuses AS ws
                    ON s.watch_status = ws.id
                WHERE
                    s.id = ?", $id)->fetchRow();

                include_once('attaches/modalSeriesInfo.php');
                echo "
                   <script>
                       $('#modalHeader h2').html('{$series->name}');
                       $('#modalHidden h2').html('{$series->name}');
                   </script>";
            }
        }
    }

    public function changeTime()
    {
        $ajax['success'] = false;
        $id = (int) $_POST['id'];
        if ($id) {
            query("UPDATE series SET last_episode_time = :last_time WHERE id = :id", [':last_time' => $_POST['time'], ':id' => $id])->execute();
            $ajax['success'] = true;
        }
        echo json_encode($ajax);
    }

    public function changeNextEpisodeDate()
    {
        $ajax['success'] = false;
        $id = (int) $_POST['id'];
        if ($id) {
            query("UPDATE series SET next_episode_date = :next_date WHERE id = :id", [':next_date' => $_POST['date'], ':id' => $id])->execute();
            $ajax['success'] = true;
        }
        echo json_encode($ajax);
    }

    public function changeWatchStatus()
    {
        $ajax['success'] = false;
        $id = (int) $_POST['id'];
        $status = $_POST['status'] ?? 1;
        $watch_status = query("SELECT name FROM WatchStatuses WHERE id = ?", $status)->fetchCell();
        if ($watch_status) {
            if ($id) {
                query("UPDATE series SET watch_status = :status WHERE id = :id", [':status' => $status, ':id' => $id])->execute();
                $ajax['success'] = true;
                $ajax['new_value'] = $watch_status;
            } else {
                $ajax['error_message'] = 'ID not found';
            }
        } else {
            $ajax['error_message'] = 'Watch status not found';
        }
        echo json_encode($ajax);
    }

    public function changeAdditionalInfo()
    {
        $ajax['success'] = false;
        if (!isset($_POST['additional_info'])) {
            $ajax['error_message'] = 'Property Additional Info not found!';
            echo json_encode($ajax);
            ApplicationHelper::exit();
        }
        if (!isset($_POST['id'])) {
            $ajax['error_message'] = 'ID not found';
            echo json_encode($ajax);
            ApplicationHelper::exit();
        }
        query("
        UPDATE
            series
        SET
            additional_info = :additional_info
        WHERE
            id = :id
        ", [
            ':id' => $_POST['id'],
            ':additional_info' => $_POST['additional_info']
        ])->execute();
        $ajax['success'] = true;
        echo json_encode($ajax);
    }
}
