<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 15/11/2015
 * Time: 8:29 AM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>


    <div class="panel panel-default">
        <article class="panel-heading">
            <h4>  Select the Main Image </h4>
            <p> Which image will be the main image for your contact </p>
        </article>
        <div class="panel-body">
            <form action="imageStore" method="post">
                <input type="hidden" name="token" value="<?php echo Output::phpOutput($data['token']) ?>">
                <input type="hidden" name="ID" value="<?php echo Output::phpOutput($data['id']) ?>">
                <?php foreach(array_chunk($data['images'], 4) as $row) { ?>
                    <div class="row top-buffer-15">
                        <?php foreach($row as $image) { ?>
                            <div class="col-md-3">
                                <input type="radio" name="radio" class="radio"
                                       value="<?php echo Output::phpOutput($image['imgImageID']) ?>" >
                                <img src="<?php echo Output::phpOutput(Links::changeToThumbnail(Links::action_link($image['imgPath']))) ; ?>"
                                     alt="<?php echo Output::phpOutput(Links::action_link($image['imgPath'])); ?>" />
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="row top-buffer-15">
                    <div class="col-md-3 col-md-offset-9">
                        <input type="submit" class="btn btn-primary">
                    </div>

                </div>
            </form>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>