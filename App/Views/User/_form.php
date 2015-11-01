<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 31/10/2015
 * Time: 10:57 PM
 */?>

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