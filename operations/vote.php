<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 14:50
 */

include 'common.php';

if (isset($_GET['possibility-id']) && is_numeric($_GET['possibility-id']) && $loggedUser) {
    $possibility = Database::getOne('possibilities', array('id' => $_GET['possibility-id']));
    $idea = Database::getOne('ideas', array('id' => $possibility->idea));
    $idea->removeVote($loggedUser);

    if ($possibility) {
        $vote = new Vote();
        $vote->possibility = $possibility->id;
        $vote->user = $loggedUser->id;
        $vote->save();
        header('location:../?message=added-vote');
    } else {
        header('location:../?error=unknown-possibility');
    }
} else {
    header('location:../');
}