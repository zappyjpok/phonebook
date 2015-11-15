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
                <th> Edit </th>
                <th> Delete </th>
            </thead>
            <tbody>
                <?php if(!is_null($data['contacts'])) { ?>
                    <?php foreach($data['contacts'] as $contact) { ?>
                    <tr>
                        <td><img src="<?php echo Output::phpOutput(Links::action_link(Links::changeToThumbnail($contact['imgPath']))) ?>"
                                 alt="<?php echo Output::phpOutput($contact['conFirstName']) ?> Image"/>
                             </td>
                        <td> <?php echo Output::phpOutput($contact['conFirstName']) ?> </td>
                        <td> <?php echo Output::phpOutput($contact['conLastName']) ?> </td>
                        <td> <?php echo Output::phpOutput($contact['conPhone']) ?> </td>
                        <td> <?php echo Output::phpOutput($contact['conEmail']) ?> </td>
                        <td>
                            <a href="<?php echo Output::phpOutput(Links::action_link('contacts/edit')) ?>"
                                class="btn btn-primary">Edit </a>
                        </td>
                        <td>
                            <form action="<?php echo Links::action_link('contacts/destroy') ?>" method="post">
                                <input type="hidden" name="ID" value="<?php echo Output::phpOutput($contact[conContactID]) ?>">
                                <input type="hidden" name="token" value="<?php echo Output::phpOutput($data['token']) ?>">
                                <input type="submit" value="Delete" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                    <?php  } ?>
                <?php } ?>
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