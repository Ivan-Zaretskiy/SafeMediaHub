<?php
class serials {
    public array $serial_categories = ['Serial', 'Anime', 'Marvel', 'CW', 'Cartoon', 'Netflix'];
    public array $watch_statuses = [];

    public function __construct()
    {
        $this->watch_statuses = getArrayQuery('SELECT * FROM watch_statuses');
    }

    private static function insertInDB($data)
    {
        return mq("
        INSERT INTO
            `serials`
        SET
            `user_id` = ".$data['user_id'].",
            `name` = '".$data['name']."',
            `category` = '".$data['category']."',
            `last_season` = ".$data['last_season'].",
            `last_episode` = ".$data['last_episode'].",
            `image_url` = ".$data['image_url'].",
            `iframe_html` = ".$data['iframe_html'].",
            `last_episode_time` = ".$data['last_episode_time'].",
            `next_episode_date` = ".$data['next_episode_date'].",
            `additional_info` = ".$data['additional_info'].",
            `watch_status` = '".$data['watch_status']."',
            `url_to_watch` = ".$data['url_to_watch'].";");
    }

    private static function updateInDB($data)
    {
        return mq("
        UPDATE
            `serials`
        SET
            `name` = '".$data['name']."',
            `category` = '".$data['category']."',
            `last_season` = ".$data['last_season'].",
            `last_episode` = ".$data['last_episode'].",
            `image_url` = ".$data['image_url'].",
            `iframe_html` = ".$data['iframe_html'].",
            `last_episode_time` = ".$data['last_episode_time'].",
            `next_episode_date` = ".$data['next_episode_date'].",
            `additional_info` = ".$data['additional_info'].",
            `watch_status` = '".$data['watch_status']."',
            `url_to_watch` = ".$data['url_to_watch']."
        WHERE
            `id` = ".$data['id'].";");
    }


    public function loadSerials()
    {
        include_once('attaches/main_serials.php');
    }

    public function getSerials()
    {
        global $user;

        $q_select = 'SELECT SQL_CALC_FOUND_ROWS `s`.*, `ws`.`name` as `full_watch_status` FROM `serials` `s` 
                LEFT JOIN `watch_statuses` `ws` ON `s`.`watch_status` = `ws`.`id`';

        $q_where = " WHERE `s`.`user_id` = ".$user->getUserID();
        $pre = " AND ";
        $q_limit = "";
        $orderBy = "";
        $orderByPre = " ORDER BY ";
        if( sizeof($_POST['columns']) > 0 ) {
            foreach ($_POST['columns'] as $column) {
                if ($column['searchable'] == "true") {
                    if ($column['search']['value'] != "" && $column['search']['value'] != '-1') {
                        $q_where .= $pre . mres($column['name']) . " LIKE '%" . mres($column['search']['value']) . "%'";
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
        $result = getArrayQuery($q);
        $total = (int) getValueQuery("SELECT FOUND_ROWS()");

        echo json_encode(["draw"=>(int) $_POST['draw'], "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $result, "q" => $q]);
        die();
    }

    public function addNewSerial()
    {
        global $user;

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $data = [];
            $data['user_id'] = $user->getUserID();
            $data['name'] = mres($_POST['name']);
            $data['category'] = mres($_POST['category']);
            $data['last_season'] = !empty($_POST['last_season']) ?  "'".(int)$_POST['last_season']."'" : 'NULL';
            $data['last_episode'] = !empty($_POST['last_episode']) ?  "'".(int)$_POST['last_episode']."'" : 'NULL';
            $data['url_to_watch'] = !empty($_POST['url_to_watch']) ?  "'".mres($_POST['url_to_watch'])."'" : 'NULL';
            $data['image_url'] = !empty($_POST['image_url']) ?  "'".mres($_POST['image_url'])."'" : 'NULL';
            $data['iframe_html'] = !empty($_POST['iframe_html']) ?  "'".mres($_POST['iframe_html'])."'" : 'NULL';
            $data['last_episode_time'] = !empty($_POST['last_episode_time']) ? "'".date_format(date_create($_POST['last_episode_time']), 'H:i:s')."'" : "'00:00:00'";
            $data['next_episode_date'] = !empty($_POST['next_episode_date']) ? "'".date_format(date_create($_POST['next_episode_date']), 'Y-m-d')."'" : "NULL";
            $data['additional_info'] = !empty($_POST['additional_info']) ?  "'".mres($_POST['additional_info'])."'" : 'NULL';
            $data['watch_status'] = !empty($_POST['watch_status']) ? (int)$_POST['watch_status'] : 1;

            $ajax['success'] = false;
            if (!empty($data['name']) && !empty($data['category'])) {
                $result = self::insertInDB($data);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $data['name'];
                } else {
                    $ajax['error_message'] = getSqliError();
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalAddNewSerial.php');
            die();
        }
    }

    public function editSerial()
    {
        $id = (int) $_GET['id'];
        if (!empty($id)) {
            if (!$_GET['ajax']) {
                $ajax = [];
                $ajax['success'] = false;
                $data = [];
                $data['id'] = $id;
                $data['name'] = mres($_POST['name']);
                $data['category'] = mres($_POST['category']);
                $data['last_season'] = !empty($_POST['last_season']) ?  "'".(int)$_POST['last_season']."'" : 'NULL';
                $data['last_episode'] = !empty($_POST['last_episode']) ?  "'".(int)$_POST['last_episode']."'" : 'NULL';
                $data['url_to_watch'] = !empty($_POST['url_to_watch']) ?  "'".mres($_POST['url_to_watch'])."'" : 'NULL';
                $data['image_url'] = !empty($_POST['image_url']) ?  "'".mres($_POST['image_url'])."'" : 'NULL';
                $data['iframe_html'] = !empty($_POST['iframe_html']) ?  "'".mres($_POST['iframe_html'])."'" : 'NULL';
                $data['last_episode_time'] = !empty($_POST['last_episode_time']) ? "'".date_format(date_create($_POST['last_episode_time']), 'H:i:s')."'" : "'00:00:00'";
                $data['next_episode_date'] = !empty($_POST['next_episode_date']) ? "'".date_format(date_create($_POST['next_episode_date']), 'Y-m-d')."'" : "NULL";
                $data['additional_info'] = !empty($_POST['additional_info']) ?  "'".mres($_POST['additional_info'])."'" : 'NULL';
                $data['watch_status'] = !empty($_POST['watch_status']) ? (int)$_POST['watch_status'] : 1;

                $ajax['success'] = false;
                if (!empty($data['name']) && !empty($data['category'])) {
                    $result = self::updateInDB($data);
                    if ($result) {
                        $ajax['success'] = true;
                        $ajax['name'] = $data['name'];
                    } else {
                        $ajax['error_message'] = getSqliError();
                    }
                }
                echo json_encode($ajax);
            } else {
                $serial = getRowQuery('SELECT * FROM `serials` WHERE `id` = ' . $id);

                include_once('attaches/modalEditSerial.php');
                die();
            }
        }
    }

    public function seasonAction()
    {
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $serial = getRowQuery("SELECT * FROM `serials` WHERE `id` = ".$id);
        if ($serial) {
            $season = (int)$_POST['season'];
            $q = "UPDATE `serials` SET `last_season` = ".$season." WHERE id = ".$id;
            if (mq($q)) {
                $ajax['success'] = true;
                $ajax['new_value'] = $season;
            } else {
                $ajax['error_message'] = getSqliError();
            }
        } else {
            $ajax['error_message'] = 'Serial Not Found!';
        }
        echo json_encode($ajax);
    }

    public function episodeAction()
    {
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $serial = getRowQuery("SELECT * FROM `serials` WHERE `id` = ".$id);
        if ($serial) {
            $episode = (int)$_POST['episode'];
            $q = "UPDATE `serials` SET `last_episode` = ".$episode." WHERE id = ".$id;
            if (mq($q)) {
                $ajax['success'] = true;
                $ajax['new_value'] = $episode;
            } else {
                $ajax['error_message'] = getSqliError();
            }
        } else {
            $ajax['error_message'] = 'Serial Not Found!';
        }
        echo json_encode($ajax);
    }

    public function info()
    {
        $id = (int)$_GET['id'];
        if (!empty($id)) {
            if ($_GET['ajax']) {
                $serial = getRowQuery('SELECT `s`.*, `ws`.`name` as `full_watch_status` FROM `serials` `s` 
                                               LEFT JOIN `watch_statuses` `ws` ON `s`.`watch_status` = `ws`.`id`
                                               WHERE `s`.`id` = ' . $id);
                include_once('attaches/modalSerialInfo.php');
                die();
            }
        }
    }

    public function changeTime()
    {
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $time = mres($_POST['time']);
        if ($id) {
            $q = 'UPDATE `serials` SET `last_episode_time` = "'.$time.'" WHERE id = '.$id;
            if (mq($q)) {
                $ajax['success'] = true;
            } else {
                $ajax['error_message'] = getSqliError();
                $ajax['q'] = $q;
            }
        }
        echo json_encode($ajax);
        die();
    }

    public function changeNextEpisodeDate()
    {
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $date = !empty($_POST['date']) ? '"'.mres($_POST['date']).'"' : 'NULL';
        if ($id) {
            $q = 'UPDATE `serials` SET `next_episode_date` = '.$date.' WHERE id = '.$id;
            if (mq($q)) {
                $ajax['success'] = true;
            } else {
                $ajax['error_message'] = getSqliError();
                $ajax['q'] = $q;
            }
        }
        echo json_encode($ajax);
        die();
    }

    public function changeWatchStatus()
    {
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $status = !empty($_POST['status']) ? (int)$_POST['status'] : 1;
        $watch_status = getValueQuery('SELECT `name` FROM `watch_statuses` WHERE `id` = '.$status);
        if ($watch_status) {
            if ($id) {
                $q = 'UPDATE `serials` SET `watch_status` = ' . $status . ' WHERE id = ' . $id;
                if (mq($q)) {
                    $ajax['success'] = true;
                    $ajax['new_value'] = $watch_status;
                } else {
                    $ajax['error_message'] = getSqliError();
                }
            } else {
                $ajax['error_message'] = 'ID not found';
            }
        } else {
            $ajax['error_message'] = 'Watch status not found';
        }
        echo json_encode($ajax);
        die();
    }
}
