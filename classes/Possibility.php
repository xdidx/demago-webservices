<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 13:16
 */
class Possibility extends Entity
{
    protected $id = null;
    protected $name = '';
    protected $idea = '';
    protected $code = '';
    protected $votes = 0;
    protected $votesPourcentage = 0;

    function __construct ($datas = array()) {
        $this->tableName = 'possibilities';
        $this->databaseFields = array('idea', 'name', 'code');
        parent::__construct($datas);

        $this->votes = Database::getAll('votes', array('possibility' => $this->id));
    }

    public function getVotesNumber () {
        return count($this->votes);
    }

    public function userAlreadyVote ($user) {
        if (is_object($user) && isset($user->id)) {
            $userId = $user->id;
        } else if (is_numeric($user)) {
            $userId = $user;
        } else {
            return null;
        }

        if (Database::getOne('votes', array('possibility' => $this->id, 'user' => $userId))) {
            return true;
        } else {
            return false;
        }
    }
}