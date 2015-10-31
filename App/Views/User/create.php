<?php
/**
 * Created by PhpStorm.
 * user: Shawn
 * Date: 30/08/2015
 * Time: 5:18 PM
 */
?>
<?php require_once('../App/Views/_layouts/_master.php') ?>




    <div class="row">
       <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <article class="panel-heading">
                    <h4>  Registration </h4>
                    <p> Please fill out the form below! </p>
                </article>
                <div class="panel-body">
                    <form action="store" method="post">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="FirstName"> First Name: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="FirstName"
                                            value="<?php echo $data['firstName'] ?>"
                                            >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="LastName"> Last Name: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="LastName"
                                           value="<?php echo $data['lastName'] ?>"
                                            >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="UserName"> Username: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="Username"
                                           value="<?php echo $data['username'] ?>"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="Email"> Email: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="Email"
                                           value="<?php echo $data['email'] ?>"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="Password"> Password: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="password" name="Password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="Confirm_Password"> Confirm Password: </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="password" name="Confirm_Password">
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
