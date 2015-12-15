<?php
if ($loggedUser) {
    ?>
    <div class="row">
        <div class="col s12">
            <?php
            if ($loggedUser->access > 0) {
                ?>
                <a class="btn waves-effect waves-light right" href="./?page=ideas-gestion&new-idea">
                    Ajouter une idée
                    <i class="material-icons right">note_add</i>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

$ideas = Database::getAll('ideas');
if (count($ideas)) {
    foreach ($ideas as $idea) {
        ?>
        <div class="card blue lighten-2">
            <ul class="card-content">
                <div class="card-title">
                    <?= $idea->name ?>
                    <?php
                    if ($loggedUser) {
                        ?>
                        <form class="right" onsubmit="return confirm('Sûr de toi?');" action="./operations/idea.php" method="post">
                            <input type="hidden" name="idea-id" value="<?= $idea->id ?>"/>
                            <input type="hidden" name="delete" value="true"/>
                            <button class="btn-floating btn-large blue" type="submit">
                                <i class="material-icons">delete</i>
                            </button>
                        </form>

                        <form class="right" action="./" method="get">
                            <input type="hidden" name="update-idea" value="<?= $idea->id ?>"/>
                            <input type="hidden" name="page" value="ideas-gestion"/>
                            <button style="right:10px;" class="btn-floating btn-large blue " type="submit">
                                <i class="material-icons">edit</i>
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                </div>

                <table class="white bordered striped highlight">
                    <thead>
                        <tr>
                            <th data-field="name">Nom</th>
                            <th data-field="count">Votes</th>
                            <th data-field="pourcent">Pourcentage</th>
                            <th data-field="actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $possibilities = $idea->getPossibilities();
                        if (count($possibilities)) {
                            foreach ($possibilities as $possibility) {
                                ?>
                                <tr>
                                    <td><?= $possibility->name ?></td>
                                    <td><?= $possibility->getVotesNumber() ?></td>
                                    <td><?= $possibility->votesPourcentage ?></td>
                                    <td>
                                        <?php
                                        if ($loggedUser) {
                                            ?>
                                            <form action="./operations/vote.php" method="get">
                                                <input type="hidden" name="possibility-id" value="<?= $possibility->id ?>"/>
                                                <button class="btn waves-effect waves-light" type="submit">
                                                    Voter
                                                    <i class="material-icons right">stars</i>
                                                </button>
                                            </form>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="connection-button" class="waves-effect waves-light btn">
                                                Se connecter
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        else {
                            ?>
                            <tr>
                                <td colspan="4">Aucune possibilité n'a encore été ajoutée</td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
} else {
    echo 'Aucune idée n\'a encore été postée';
}