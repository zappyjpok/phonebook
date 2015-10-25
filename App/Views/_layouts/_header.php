<?php
/**
 * Created by PhpStorm.
 * User: Shawn
 * Date: 3/09/2015
 * Time: 5:30 PM
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clothing Mart</title>

    <link rel="stylesheet" href="<?php echo Links::action_link('CSS/app.css') ?>">

</head>
    <body>
        <main class="container">
            <?php require_once('../App/Views/_layouts/_nav.php') ?>
            <?php require_once('../App/Views/_layouts/_shoppingCart.php'); ?>