<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 19/11/2015
 * Time: 9:29 PM
 */ ?>

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
                    <a href="<?php echo Output::phpOutput(Links::action_link('contacts/edit/' .
                        Output::phpOutput($contact['conContactID']))) ?>"
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