<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 11:24
 */
class Database
{
    private static $connection = null;
    private static $host = '127.0.0.1';
    private static $dbname = 'gta5-demago';
    private static $username = 'root';
    private static $password = '';
    private static $classAssociations = array('users' => 'User', 'ideas' => 'Idea', 'votes' => 'Vote', 'possibilities' => 'Possibility', 'visits' => 'Visit');

    public static function insert($table = null, $fields = array()) {
        if (!$table || !is_array($fields) || count($fields) == 0) {
            return;
        }

        $fieldsString = '';
        $interrogationString = '';
        $values = array();

        foreach ($fields as $fieldName => $fieldValue) {
            $fieldsString .= $fieldName.',';
            $interrogationString .= '?,';
            $values[] = $fieldValue;
        }
        $fieldsString = substr($fieldsString, 0, strlen($fieldsString) - 1);
        $interrogationString = substr($interrogationString, 0, strlen($interrogationString) - 1);

        $insertRequest = Database::getConnection()->prepare('INSERT INTO '.$table.'('.$fieldsString.') VALUES('.$interrogationString.')');
        $insertRequest->execute($values);
        return Database::getConnection()->lastInsertId();
    }

    public static function update ($table = null, $id = null, $fields = array()) {

        if (!$table || !$id || !is_numeric($id) || !is_array($fields) || count($fields) == 0) {
            return;
        }

        $fieldsString = '';
        $values = array();

        foreach ($fields as $fieldName => $fieldValue) {
            $fieldsString .= $fieldName.' = ?,';
            $values[] = $fieldValue;
        }
        $fieldsString = substr($fieldsString, 0, strlen($fieldsString) - 1);

        $values[] = $id;

        $updateRequest = Database::getConnection()->prepare('UPDATE '.$table.' SET '.$fieldsString.' WHERE id = ?');
        $updateRequest->execute($values);
    }

    public static function delete ($table = null, $fields = array()) {
        if (!$table || !is_array($fields) || count($fields) == 0) {
            return;
        }

        $values = array();
        $where = ' WHERE ';
        foreach ($fields as $fieldName => $fieldValue) {
            $where .= $fieldName.' = ? AND ';
            $values[] = $fieldValue;
        }
        $where = substr($where, 0, strlen($where) - 4);

        $updateRequest = Database::getConnection()->prepare('DELETE FROM '.$table.$where);
        $updateRequest->execute($values);
    }

    public static function getConnection () {
        if (Database::$connection == null) {
            try {
                Database::$connection = new PDO('mysql:dbname=' . Database::$dbname . ';host=' . Database::$host, Database::$username, Database::$password);
            } catch (Exception $e) {
                die('Database connection failed');
            }
        }
        return Database::$connection;
    }

    public static function visit ($user)
    {
        $userId = 0;
        if ($user != null && is_object($user)) {
            $userId = $user->id;
        }
        $values = array($userId, $_SERVER['REMOTE_ADDR'], time());
        $visitRequest = Database::getConnection()->prepare('INSERT INTO visits(user, ip, date) values(?, ?, ?)');
        $visitRequest->execute($values);
    }

    public static function getAll ($table = null, $conditions = array(), $orderByArray = array(), $jsonSerialization = false) {
        if (!$table) {
            return;
        }

        $values = array();
        $orderBy = '';
        $where = '';

        if (count($conditions)) {
            $where = ' WHERE ';
            foreach ($conditions as $fieldName => $fieldValue) {
                $where .= $fieldName . ' = ? AND ';
                $values[] = $fieldValue;
            }
            $where = substr($where, 0, strlen($where) - 4);
        }

        if (count($orderByArray)) {
            $orderBy = ' ORDER BY  ';
            foreach ($orderByArray as $fieldName => $order) {
                $orderBy .= $fieldName . ' ' . $order . ',';
            }
            $orderBy = substr($orderBy, 0, strlen($orderBy) - 1);
        }

        $returnArray = array();
        if (isset(Database::$classAssociations[$table])) {
            $className = Database::$classAssociations[$table];
            $selectRequest = Database::getConnection()->prepare('SELECT * FROM '.$table.$where.$orderBy);
            $selectRequest->execute($values);
            while($selectDatas = $selectRequest->fetch()) {
                $currentObject = new $className($selectDatas);
                if ($jsonSerialization) {
                    $currentObject = $currentObject->toArray();
                    foreach($currentObject as $key => $val){
                        $currentObject[$key] = utf8_decode($val);
                    }
                }
                $returnArray[] = $currentObject;
            }
        }

        return $returnArray;
    }

    public static function getOne ($table = null, $conditions = array()) {
        if (!$table) {
            return null;
        }
        $results = Database::getAll($table, $conditions);
        if (count($results)) {
            return array_shift($results);
        } else {
            return null;
        }
    }

}