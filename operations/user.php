<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 0/10/2015
 * Time: 14:20
 */

include 'common.php';

if (isset($_POST['username'], $_POST['password'])) {
    if (isset($_POST['password_confirmation'])) {
        $user = Database::getOne('users', array('username' => $_POST['username']));
        if ($user) {
            header('location:../?error=username-exists');
        } else if ($_POST['password'] == $_POST['password_confirmation']) {
            $user = new User();
            $user->username = $_POST['username'];
            $user->password = md5($_POST['password']);
            $user->save();

            $_SESSION['userId'] = $user->id;
            header('location:../');
        } else {
            header('location:../?error=inccorect-confirmation');
        }
    } else {
        $user = Database::getOne('users', array('username' => $_POST['username'], 'password' => md5($_POST['password'])));
        if ($user) {
            $_SESSION['userId'] = $user->id;
            header('location:../?message=logged');
        } else {
            unset($_SESSION['userId']);
            header('location:../?error=unknown-user');
        }
    }
} else if (isset($_POST['username'], $_POST['password'], $_POST['password_confirmation'])) {
    unset($_SESSION['userId']);
    header('location:../?message=disconnected');
} else if (isset($_POST['disconnect'])) {
    unset($_SESSION['userId']);
    header('location:../?message=disconnected');
} else {
    header('location:../');
}