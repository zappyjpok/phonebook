<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 7/11/2015
 * Time: 4:28 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>

    <div class="panel panel-default">
        <article class="panel-heading">
            <h4>  Add a Contact </h4>
            <p> Fill out the form below and click submit </p>
        </article>
        <div class="panel-body">
            <form action="<?php echo Links::action_link('contacts/store/'); ?>" method="post" enctype="multipart/form-data">
                <?php require_once('../App/Views/contact/_form.php') ?>
                <div class="row form-group">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-1">
                            <label for="Image"> Image: </label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="Image[]" multiple>
                        </div>
                        <div class="col-md-2 col-md-offset-4">
                            <input type="submit" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>