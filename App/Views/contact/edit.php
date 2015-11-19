<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 7/11/2015
 * Time: 4:28 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>

    <div class="panel panel-default">
        <article class="panel-heading">
            <h4>  Title </h4>
            <p> Subtitle </p>
        </article>
        <div class="panel-body">
            <form action="<?php echo Links::action_link('contacts/update/'); ?>" method="post">
                <?php require_once('../App/Views/contact/_form.php') ?>
                <div class="row form-group">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-9">
                            <input type="submit" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>