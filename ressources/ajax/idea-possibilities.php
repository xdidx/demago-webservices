<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 14:10
 */

function __autoload($className) {
    include '../../classes/'.$className . '.php';
}

$test = array();
$return = array();

$test['count'] = 0;
if (isset($_POST['idea-id']) && is_numeric($_POST['idea-id'])) {
    $return['possibilities'] = Database::getAll('possibilities', array('idea' => $_POST['idea-id']), array(), 'json_serialize');
}

echo json_encode($return);