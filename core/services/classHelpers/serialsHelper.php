<?php
class serialsHelper {

    public static function getWatchStatuses() {
        $local = MemcacheHelper::get('WatchStatuses');
        if ($local) {
            return $local;
        }
        $statuses = query("SELECT * FROM watch_statuses")->fetchAll();
        MemcacheHelper::set('WatchStatuses', $statuses, 360);
        return $statuses;
    }

    protected static function getSerialCategories(): array {
        return ['Serial', 'Anime', 'Marvel', 'CW', 'Cartoon', 'Netflix'];
    }
}
