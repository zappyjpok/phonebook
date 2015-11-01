<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 31/10/2015
 * Time: 10:56 PM
 */?>

<?php require_once('../App/Views/_layouts/_master.php') ?>




    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <article class="panel-heading">
                    <h4>  Registration </h4>
                    <p> Please fill out the form below! </p>
                </article>
                <div class="panel-body">
                    <form action="update" method="post">

                        <?php require_once('../App/Views/user/_form.php') ?>

                        <div class="row form-group">
                            <div class="col-md-6 col-md-offset-6">
                                <input type="submit" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>