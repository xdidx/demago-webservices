<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 02/10/2015
 * Time: 14:15
 */

include 'common.php';

if (isset($_POST['idea-id'], $_POST['name'], $_POST['description'])) {
    if (is_numeric($_POST['idea-id'])) {
        $idea = Database::getOne('ideas', array('id' => $_POST['idea-id']));
    } else {
        $idea = new Idea();
    }

    if ($idea) {
        $idea->name = $_POST['name'];
        $idea->description = $_POST['description'];
        $idea->save();

        $possibilities = Database::getAll('possibilities', array('idea' => $_POST['idea-id']));
        foreach ($possibilities as $possibility) {
            if (isset($_POST['possibility_' . $possibility->id]) && isset($_POST['possibility_code_' . $possibility->id]) && !empty($_POST['possibility_' . $possibility->id])) {
                $possibility->name = htmlspecialchars($_POST['possibility_' . $possibility->id]);
                $possibility->code = htmlspecialchars($_POST['possibility_code_' . $possibility->id]);
                $possibility->save();
            } else {
                $possibility->delete();
            }
        }

        foreach ($_POST['possibility_new'] as $index => $possibilityName) {
            if (!empty($possibilityName)) {
                $possibility = new Possibility();
                $possibility->idea = $idea->id;
                $possibility->name = htmlspecialchars($possibilityName);
                $possibility->code = htmlspecialchars($_POST['possibility_code_new'][$index]);
                $possibility->save();
            }
        }
    }

    header('location:../?message=added-idea');
} else if (isset($_POST['idea-id'], $_POST['delete']) && is_numeric($_POST['idea-id'])) {
    $idea = Database::getOne('ideas', array('id' => $_POST['idea-id']));
    if ($idea) {
        $idea->delete();
    }
    header('location:../?message=deleted-idea');
} else {
    header('location:../?error=bad-parameters');
}