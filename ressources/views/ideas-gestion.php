<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 30/09/2015
 * Time: 13:49
 */

if (isset($_GET['update-idea']) && is_numeric($_GET['update-idea'])) {
    $idea = Database::getOne('ideas', array('id' => $_GET['update-idea']));
} else {
    $idea = new Idea();
}

if ($idea) {
    ?>
    <form action="./operations/idea.php" method="post">

        <div class="row">
            <div class="col l6 m5 s12">
                <div class="card grey lighten-3">
                    <div class="card-content">
                        <div class="card-title grey-text text-darken-4">Idée</div>

                        <div class="input-field">
                            <label for="name">Nom :</label>
                            <input type="text" id="name" name="name" value="<?= $idea->name ?>"/>
                        </div>

                        <div class="input-field">
                            <label for="description">Description :</label>
                            <textarea name="description" id="description"><?= $idea->description ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l6 m7 s12">
                <div class="card grey lighten-3">
                    <div class="card-content">
                        <div class="card-title grey-text text-darken-4">Réponses possibles</div>

                        <div id="idea-possibilites"></div>

                        <div class="center-align">
                            <div class="btn waves-effect waves-light center-align" id="add-possibility">
                                Ajouter une possibilité
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12 center-align">
                <input type="hidden" name="idea-id" id="idea-id" value="<?= $idea->id ?>"/>

                <button class="btn waves-effect waves-light green" type="submit">
                    Valider les modification
                </button>
                <a href="./" class="btn waves-effect waves-light red lighten-2">
                    Annuler
                </a>
            </div>
        </div>

    </form>
    <?php
} else {
    header('location:./?error=idea-doesnt-exist');
}