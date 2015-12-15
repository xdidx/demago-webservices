<?php

/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 11:22
 */
class Idea extends Entity
{
    protected $id = null;
    protected $user = '';
    protected $name = '';
    protected $description = '';
    private $votesNumber = 0;
    private $possibilities = array();

    function __construct($datas = array())
    {
        $this->tableName = 'ideas';
        $this->databaseFields = array('user', 'name', 'description');
        $this->fieldsToConvertIntoObject = array('user' => 'users');
        parent::__construct($datas);
        $this->updatePossibilities();
    }

    private function updatePossibilities()
    {
        $this->possibilities = Database::getAll('possibilities', array('idea' => $this->id));

        $this->votesNumber = 0;
        foreach ($this->possibilities as $possibility) {
            if(is_object($possibility))
            $this->votesNumber += $possibility->getVotesNumber();
        }

        foreach ($this->possibilities as $possibility) {
            if(is_object($possibility))
            if ($this->votesNumber > 0) {
                $possibility->votesPourcentage = round($possibility->getVotesNumber() / $this->votesNumber * 100, 2);
            } else {
                $possibility->votesPourcentage = 0;
            }
        }
    }

    public function getPossibilities($order = 'ASC')
    {
        return $this->possibilities;
    }

    public function removeVote ($user) {
        if (is_object($user) && is_numeric($user->id)) {
            $user = $user->id;
        }

        foreach ($this->possibilities as $possibility) {
            Database::delete('votes', array('possibility' => $possibility->id, 'user' => $user));
        }
    }

    public function userAlreadyVote ($user) {
        foreach ($this->possibilities as $possibility) {
            if ($possibility->userAlreadyVote($user)) {
                return true;
            }
        }
    }
}