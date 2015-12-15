<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 11:22
 */
class Vote extends Entity
{
    protected $id = null;
    protected $possibility = '';
    protected $user = '';
    protected $date = '';

    function __construct ($datas = array()) {
        $this->tableName = 'votes';
        $this->databaseFields = array('possibility', 'user', 'date');
        $this->fieldsToConvertIntoObject = array('user' => 'users');
        parent::__construct($datas);
    }

    public function save()
    {
        if (!$this->isSaved()) {
            $this->date = date('Y-m-d H:i:s');
        }
        parent::save();
    }
}