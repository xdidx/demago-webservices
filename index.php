<?php
function __autoload($className) {
    include 'classes/'.$className . '.php';
}

$projectUrl = 'http://nesblog.com/gta-demago/';

session_start();

$loggedUser = null;
if (isset($_SESSION['userId']) && is_numeric($_SESSION['userId'])) {
    $loggedUser = Database::getOne('users', array('id' => $_SESSION['userId']));
}

Database::visit($loggedUser);
?>

<html>
    <head>
        <title>GTA Demago</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="ressources/css/materialize.min.css">
        <link rel="stylesheet" type="text/css" href="ressources/css/main.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>

        <header class="center-align">
            <img height="200" src="./ressources/images/gta_demago.png" alt="Logo GTA Demago"/>
        </header>

        <nav class="center-align">
            <ul style="display: inline-block;">
                <li><a href="./?page=welcome">Accueil</a></li>
                <li><a href="http://gtavdemago.forumactif.org/">Forum</a></li>
                <li><a href="./?page=launcher">Launcher</a></li>
                <li><a href="./?page=mod">Mod</a></li>
                <li><a href="./?page=web">Services web</a></li>
                <li><a href="./?page=remote">Remote Gaming</a></li>
                <li><a href="./?page=team">L'équipe</a></li>
            </ul>
        </nav>

        <main class="container">
            <div class="row">
                <div class="col s12 green lighten-1 card">
                    <?php
                    if (isset($_GET['message'])) {
                        ?>
                        <div class="card-content">
                            <?php
                            $message = 'L\'action a été réussie';
                            if ($_GET['message'] == 'added-idea') {
                                $message = 'L\'idée a été ajoutée';
                            }
                            if ($_GET['message'] == 'added-vote') {
                                $message = 'Le vote a été pris en compte';
                            }
                            if ($_GET['message'] == 'disconnected') {
                                $message = 'Vous avez été déconnecté';
                            }
                            if ($_GET['message'] == 'logged') {
                                $message = 'Connexion réussie';
                            }
                            if ($_GET['message'] == 'deleted-idea') {
                                $message = 'Idée supprimée';
                            }
                            echo $message;
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="col s12 red lighten-1 card">
                    <?php
                    if (isset($_GET['error'])) {
                        ?>
                        <div class="card-content">
                            <?php
                            $message = 'L\'action a échouée';
                            if ($_GET['error'] == 'idea-exists') {
                                $message = 'L\'idée n\'existe plus';
                            }
                            if ($_GET['error'] == 'username-exists') {
                                $message = 'Le nom d\'utilisateur existe déjà';
                            }
                            if ($_GET['error'] == 'unknown-user') {
                                $message = 'Le nom d\'utilisateur ou mot de passe inccorect';
                            }
                            if ($_GET['error'] == 'inccorect-confirmation') {
                                $message = 'La confirmation du mot de passe est inccorecte';
                            }
                            if ($_GET['error'] == 'bad-parameters') {
                                $message = 'Les paramètres envoyés sont inccorects';
                            }

                            echo $message;
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <?php

            if (isset($_GET['page']) && file_exists('ressources/views/'.$_GET['page'].'.php')) {
                include 'ressources/views/'.$_GET['page'].'.php';
            } else {
                include 'ressources/views/welcome.php';
            }
            ?>
        </main>

        <div id="shadow"></div>

        <script src="ressources/js/jquery.js"></script>
        <script src="ressources/js/materialize.min.js"></script>
        <script src="ressources/js/main.js"></script>
    </body>
</html>