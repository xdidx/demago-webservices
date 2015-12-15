<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 11:22
 */
class User extends Entity
{
    protected $id = null;
    protected $password = '';
    protected $username = '';
    protected $email = '';
    protected $access = 0;

    function __construct ($datas = array()) {
        $this->tableName = 'users';
        $this->databaseFields = array('password', 'username', 'email');
        parent::__construct($datas);
    }

    function __toString () {
        return $this->username;
    }
}