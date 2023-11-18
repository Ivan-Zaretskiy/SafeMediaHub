<?php
class notes {

    public function main() {
        include_once('attaches/main.php');
    }

    public static function insertInDB($encryptedName, $encryptedValue) {
        return query("
        INSERT INTO
            notes
        SET
            user_id = :user_id,
            name = :name,
            value = :encryptedValue
        ", [
            ":user_id" => SessionUser::getUserID(),
            ":name" => $encryptedName,
            ":encryptedValue" => $encryptedValue
        ])->execute();
    }

    public function addCustomField() {

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $fieldName = $_POST['fieldName'];
            $fieldValue = $_POST['fieldValue'];

            $ajax['success'] = false;
            if (!empty($fieldName) && !empty($fieldValue)) {
                $encryptedName = KeyHelper::encryptString($fieldName);
                $encryptedValue = KeyHelper::encryptString($fieldValue);
                $result = self::insertInDB($encryptedName, $encryptedValue);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $fieldName;
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalAddKeysField.php');
        }
    }

    public function getFields() {
        $result = query("SELECT * FROM notes WHERE user_id = ?", SessionUser::getUserID())->fetchAll();
        foreach ($result as $item) {
            $item->name = KeyHelper::decryptString($item->name);
        }

        echo json_encode(['data'=>$result]);
    }

    public function editField() {
        if (!$_GET['ajax']) {
            $id = (int)$_GET['id'];
            $ajax = [];
            $fieldName = $_POST['fieldName'];
            $fieldValue = $_POST['fieldValue'];
            $encryptedName = KeyHelper::encryptString($fieldName);
            $encryptedValue = KeyHelper::encryptString($fieldValue);
            query('
            UPDATE
                notes
            SET
                encryptedValue = :encryptedValue,
                name = :name
            WHERE
                id = :id
            ', [
                ":id" => $id,
                ":name" => $encryptedName,
                ":encryptedValue" => $encryptedValue,
            ])->execute();
            $ajax['success'] = true;
            $ajax['name'] = $fieldName;
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalEditField.php');
        }
    }
}
