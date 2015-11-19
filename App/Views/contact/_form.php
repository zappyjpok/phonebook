<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 17/11/2015
 * Time: 6:05 PM
 */ ?>

<input type="hidden" name="token" value="<?php echo Output::phpOutput($data['token']); ?>">
<?php if(isset($data['ID'])) { ?>
    <input type="hidden" name="ID" value="<?php echo Output::phpOutput($data['ID']) ?>">
<?php } ?>
<div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
        <label for="FirstName"> First Name</label>
    </div>
    <div class="col-md-3">
        <input type="text" name="FirstName" value="<?php if(isset($data['firstName'])) { echo Output::phpOutput($data['firstName']); } ?>">
    </div>
    <div class="col-md-2 col-md-offset-1">
        <label for="LastName"> Last Name</label>
    </div>
    <div class="col-md-3">
        <input type="text" name="LastName" value="<?php if(isset($data['lastName'])) { echo Output::phpOutput($data['lastName']); } ?>">
    </div>
</div>
<div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
        <label for="Email"> Email: </label>
    </div>
    <div class="col-md-3">
        <input type="text" name="Email" value="<?php if(isset($data['email'])) { echo Output::phpOutput($data['email']); } ?>">
    </div>
    <div class="col-md-2 col-md-offset-1">
        <label for="Phone"> Phone: </label>
    </div>
    <div class="col-md-3">
        <input type="text" name="Phone" value="<?php if(isset($data['phone'])) { echo Output::phpOutput($data['phone']); } ?>">
    </div>
</div>

