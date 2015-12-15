<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 11:22
 */
class Visit extends Entity
{
    protected $id = null;
    protected $ip = '';
    protected $date = '';
    protected $user = '';

    function __construct ($datas = array()) {
        $this->tableName = 'visits';
        $this->databaseFields = array('ip', 'user', 'date');
        $this->fieldsToConvertIntoObject = array('user' => 'users');
        parent::__construct($datas);
    }
}