<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 02/10/2015
 * Time: 14:20
 */

function __autoload($className) {
    include '../classes/'.$className . '.php';
}
session_start();

$loggedUser = null;
if (isset($_SESSION['userId']) && is_numeric($_SESSION['userId'])) {
    $loggedUser = Database::getOne('users', array('id' => $_SESSION['userId']));
}