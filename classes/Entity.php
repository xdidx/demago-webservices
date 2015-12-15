<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 12:10
 */
class Entity
{
    protected $tableName = null;
    protected $databaseFields = null;
    protected $fieldsToConvertIntoObject = array();
    protected $saved = false;

    function __construct($datas = array())
    {
        if (!$this->tableName || !$this->databaseFields) {
            return;
        }
        if (is_array($datas) && count($datas) > 0) {
            foreach ($datas as $fieldName => $fieldValue) {
                if (isset($this->fieldsToConvertIntoObject[$fieldName])) {
                    $tableName = $this->fieldsToConvertIntoObject[$fieldName];
                    $this->$fieldName = Database::getOne($tableName, array('id' => $fieldValue));
                } else {
                    $this->$fieldName = $fieldValue;
                }
            }
        }
        if (isset($this->id) && is_numeric($this->id)) {
            $this->saved = true;
        }
    }

    public function save()
    {
        if (!$this->tableName || !$this->databaseFields) {
            return;
        }

        $invalid = false;
        $values = array();
        foreach ($this->databaseFields as $fieldName) {
            if (isset($this->$fieldName)) {
                if (is_object($this->$fieldName)) {
                    $values[$fieldName] = $this->$fieldName->id;
                } else {
                    $values[$fieldName] = $this->$fieldName;
                }
            }
        }

        if (!$invalid) {
            if ($this->id == null) {
                $this->id = Database::insert($this->tableName, $values);
            } else {
                Database::update($this->tableName, $this->id, $values);
            }
            $this->saved = true;
        }
    }

    public function delete()
    {
        Database::delete($this->tableName, array('id' => $this->id));
    }

    public function isSaved()
    {
        return $this->saved;
    }

    public function __get($property)
    {
        if (isset($this->$property)) {
            return $this->$property;
        } else {
            return null;
        }
    }

    public function __set($property, $value)
    {
        $this->saved = false;
        if (isset($this->$property)) {
            $this->$property = $value;
        }
    }

    public function __toString()
    {
        if (isset($this->name)) {
            return ''.$this->name;
        } else {
            return ''.$this->id;
        }
    }

    public function toArray()
    {
        $return = array();
        $return['id'] = $this->id;
        foreach ($this->databaseFields as $fieldName) {
            if (isset($this->$fieldName)) {
                $return[$fieldName] = utf8_encode($this->$fieldName);
            }
        }
        return $return;
    }
}