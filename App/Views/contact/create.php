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
            <form action="<?php echo Links::action_link('contacts/store/' . $_SESSION['user']['user_id']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $data['token']; ?>">
                <div class="row form-group">
                    <div class="col-md-2 col-md-offset-1">
                        <label for="FirstName"> First Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="FirstName" value="<?php if(isset($data['firstName'])) { echo $data['firstName']; } ?>">
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <label for="LastName"> Last Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="LastName" value="<?php if(isset($data['lastName'])) { echo $data['lastName']; } ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2 col-md-offset-1">
                        <label for="Email"> Email: </label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="Email" value="<?php if(isset($data['email'])) { echo $data['email']; } ?>">
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <label for="Phone"> Phone: </label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="Phone" value="<?php if(isset($data['phone'])) { echo $data['phone']; } ?>">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-1">
                            <label for="Image"> Image: </label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="image">
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