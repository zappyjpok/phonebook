<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 17/11/2015
 * Time: 8:57 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>

    <div class="panel panel-default">
        <article class="panel-heading">
            <h4>  Add more photos </h4>
            <p> You can add more photos or choose skip to continue </p>
        </article>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="<?php echo Links::action_link('contacts/addPhotos'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo Output::phpOutput($data['id']) ?>" name="id">
                        <input type="hidden" value="<?php echo Output::phpOutput($data['token']) ?>" name="token">
                        <div class="row form-group">
                            <div class="row">
                                <div class="col-md-2 col-md-offset-1">
                                    <label for="Image"> Images: </label>
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
                <div class="col-md-2 col-md-offset-4">
                    <a href="<?php Links::action_link('contacts/index'); ?>" class="btn btn-success">Skip</a>
                </div>
            </div>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>