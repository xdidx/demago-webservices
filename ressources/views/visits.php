<style>
    .visit {
        overflow: auto;;
        border: 1px solid grey;
    }
    .visit>div {
        float: left;
        margin: 3px;
        padding: 3px;
        width: 200px;
    }
</style>

<?php
/**
 * Created by PhpStorm.
 * User: DiD
 * Date: 12/10/2015
 * Time: 11:02
 */

$visits = Database::getAll('visits', array(), array('date' => 'DESC'));
if (count($visits)) {
    foreach ($visits as $visit) {
        ?>
        <div class="visit">
            <div>
                <?php
                if ($visit->user) {
                    echo $visit->user;
                } else {
                    echo 'Visiteur';
                }
                ?>
            </div>
            <div>
                <?= date('d/m/Y H:i', $visit->date); ?>
            </div>
            <div>
                <?= $visit->ip; ?>
            </div>
        </div>
        <?php
    }
} else {
    echo 'Aucune visite n\'a encore été postée';
}