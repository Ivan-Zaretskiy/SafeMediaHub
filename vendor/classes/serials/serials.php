<?php
class serials {
    public $user;
    public $serial_categories = ['Serial','Anime', 'Marvel', 'CW'];

    public function __construct()
    {
        $this->user = $_SESSION['loginUser'];
    }

    private static function insertInDB($userId, $name, $category, $last_season, $last_episode, $last_episode_time, $next_episode_date, $url_to_watch, $image_url, $iframe_html, $is_planned, $is_finished)
    {
        $q = "INSERT INTO `serials` SET `user_id` = ".(int)$userId.", `name` = '".$name."', `category` = '".$category."', `last_season` = ".$last_season.", `last_episode` = ".$last_episode.", `image_url` = ".$image_url.", `iframe_html` = ".$iframe_html.",".
              "`last_episode_time` = ".$last_episode_time.", `next_episode_date` = ".$next_episode_date.", `is_planned` = '".$is_planned."', `is_finished` = '".$is_finished."', `url_to_watch` = ".$url_to_watch;
        return mq($q);
    }

    private static function updateInDB($name, $category, $last_season, $last_episode, $last_episode_time, $next_episode_date, $url_to_watch, $image_url, $iframe_html, $is_planned, $is_finished, $id)
    {
        $q = "UPDATE `serials` SET `name` = '".$name."', `category` = '".$category."', `last_season` = ".$last_season.", `last_episode` = ".$last_episode.", `url_to_watch` = ".$url_to_watch.", `image_url` = ".$image_url.", `iframe_html` = ".$iframe_html.",".
              "`last_episode_time` = ".$last_episode_time.", `next_episode_date` = ".$next_episode_date.", `is_planned` = '".$is_planned."', `is_finished` = '".$is_finished."' WHERE `id` = ".$id;
        return mq($q);
    }


    public function loadSerials()
    {
        include_once('attaches/main_serials.php');
    }

    public function getSerials()
    {
        $q = 'SELECT * FROM `serials` WHERE `user_id` = '.$this->user['id'];
        $result = getArrayQuery($q);

        echo json_encode(['data'=>$result]);
    }

    public function addNewSerial()
    {
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $userId = $this->user['id'];
            $name = mres($_POST['name']);
            $category = mres($_POST['category']);
            $last_season = !empty($_POST['last_season']) ?  "'".(int)$_POST['last_season']."'" : 'NULL';
            $last_episode = !empty($_POST['last_episode']) ?  "'".(int)$_POST['last_episode']."'" : 'NULL';
            $url_to_watch = !empty($_POST['url_to_watch']) ?  "'".mres($_POST['url_to_watch'])."'" : 'NULL';
            $image_url = !empty($_POST['image_url']) ?  "'".mres($_POST['image_url'])."'" : 'NULL';
            $iframe_html = !empty($_POST['iframe_html']) ?  "'".mres($_POST['iframe_html'])."'" : 'NULL';
            $last_episode_time = !empty($_POST['last_episode_time']) ? "'".date_format(date_create($_POST['last_episode_time']), 'H:i:s')."'" : "NULL";
            $next_episode_date = !empty($_POST['next_episode_date']) ? "'".date_format(date_create($_POST['next_episode_date']), 'Y-m-d')."'" : "NULL";
            $is_planned = (int)isset($_POST['is_planned']);
            $is_finished = (int)isset($_POST['is_finished']);

            $ajax['success'] = false;
            if (!empty($name) && !empty($category)) {
                $result = self::insertInDB($userId, $name, $category, $last_season, $last_episode, $last_episode_time, $next_episode_date, $url_to_watch, $image_url, $iframe_html, $is_planned, $is_finished);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $name;
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
        $id = (int)$_GET['id'];
        if (!empty($id)) {
            if (!$_GET['ajax']) {
                $ajax = [];
                $ajax['success'] = false;
                $name = mres($_POST['name']);
                $category = mres($_POST['category']);
                $last_season = !empty($_POST['last_season']) ?  "'".(int)$_POST['last_season']."'" : 'NULL';
                $last_episode = !empty($_POST['last_episode']) ?  "'".(int)$_POST['last_episode']."'" : 'NULL';
                $url_to_watch = !empty($_POST['url_to_watch']) ?  "'".mres($_POST['url_to_watch'])."'" : 'NULL';
                $image_url = !empty($_POST['image_url']) ?  "'".mres($_POST['image_url'])."'" : 'NULL';
                $iframe_html = !empty($_POST['iframe_html']) ?  "'".mres($_POST['iframe_html'])."'" : 'NULL';
                $last_episode_time = !empty($_POST['last_episode_time']) ? "'".date_format(date_create($_POST['last_episode_time']), 'H:i:s')."'" : "NULL";
                $next_episode_date = !empty($_POST['next_episode_date']) ? "'".date_format(date_create($_POST['next_episode_date']), 'Y-m-d')."'" : "NULL";
                $is_planned = (int)isset($_POST['is_planned']);
                $is_finished = (int)isset($_POST['is_finished']);

                $ajax['success'] = false;
                if (!empty($name) && !empty($category)) {
                    $result = self::updateInDB($name, $category, $last_season, $last_episode, $last_episode_time, $next_episode_date, $url_to_watch, $image_url, $iframe_html, $is_planned, $is_finished, $id);
                    if ($result) {
                        $ajax['success'] = true;
                        $ajax['name'] = $name;
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
                $serial = getRowQuery('SELECT * FROM `serials` WHERE `id` = ' . $id);
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
}
