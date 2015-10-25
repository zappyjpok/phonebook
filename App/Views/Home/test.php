<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 27/09/2015
 * Time: 3:52 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_header.php') ?>

    <h1> Session data </h1>

    <p>
        Time1:
        <?php echo $data['time1'];?>
    </p>

    <p>
        Time2: <?php echo $data['time2']; ?>
    </p>

        <pre>
            <?php
            print_r($_SESSION)
            ?>
        </pre>

     </p>

        <pre>
            <?php
                print_r($data['messages'])
            ?>
        </pre>

<?php require_once('../App/Views/_layouts/_footer.php') ?>