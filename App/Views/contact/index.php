<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 7/11/2015
 * Time: 4:28 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_master.php') ?>
    <div class="row">
        <a  href="<?php echo Links::action_link('contacts/create/' . $_SESSION['user']['user_id']) ?>" class="btn btn-primary">Add a Contact </a>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <th> Image </th>
                <th> First Name </th>
                <th> Last Name </th>
                <th> Phone Number </th>
                <th> Email</th>
            </thead>
            <tbody>
                <?php foreach($data['contacts'] as $contact) { ?>
                <tr>
                    <td> <?php  ?> </td>
                    <td> <?php echo Output::phpOutput($contact['conFirstName']) ?> </td>
                    <td> <?php echo Output::phpOutput($contact['conLastName']) ?> </td>
                    <td> <?php echo Output::phpOutput($contact['conPhone']) ?> </td>
                    <td> <?php echo Output::phpOutput($contact['conEmail']) ?> </td>
                </tr>
                <?php  } ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-5 col-md-offset-4">
            <ul class="pagination">
                <?php  for($i = 1; $i < $data['pages'] + 1; $i++) {
                    if($data['page'] == $i) { ?>
                    <li class="active">
                        <a href="<?php echo Links::action_link('contacts/index/' . $i); ?>"> <?php echo Output::phpOutput($i); ?> </a>
                    </li>
                    <?php } else { ?>
                    <li>
                        <a href="<?php echo Links::action_link('contacts/index/' . $i); ?>"> <?php echo Output::phpOutput($i); ?> </a>
                    </li>
                <?php }
                    } ?>
            </ul>
        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>