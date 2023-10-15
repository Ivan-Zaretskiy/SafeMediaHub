<?php
class seriesHelper {

    public static function getWatchStatuses() {
        $local = MemcacheHelper::get('WatchStatuses');
        if ($local) {
            return $local;
        }
        $statuses = query("SELECT * FROM WatchStatuses")->fetchAll();
        MemcacheHelper::set('WatchStatuses', $statuses, 360);
        return $statuses;
    }

    protected static function getSeriesCategories(): array {
        return ['Series', 'Anime', 'Marvel', 'CW', 'Cartoon', 'Netflix'];
    }
}
