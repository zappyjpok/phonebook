<?php
/**
 * Created by PhpStorm.
 * user: Shawn
 * Date: 3/09/2015
 * Time: 6:00 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <article class="panel-heading">
                    <h4>  Login </h4>
                    <p> Please enter your password and email below </p>
                </article>
                <div class="panel-body">
                    <form action="check" method="post">
                        <input type="hidden" name="token" value="<?php echo $data['token'] ?>">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="Email"> Email: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="Email"
                                               value="<?php if(isset($data['email'])){ echo $data['email']; } ?>"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="Password"> Password: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="Password">
                                    </div>
                                </div>
                            </div>
                        </div>
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