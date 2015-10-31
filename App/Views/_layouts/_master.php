<?php
/**
 * Created by PhpStorm.
 * user: Shawn Legge
 * Date: 25/10/2015
 * Time: 4:32 PM
 */ ?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Clothing Mart</title>

        <link rel="stylesheet" href="<?php echo Links::action_link('CSS/app.css') ?>">

    </head>
    <body>
        <main class="container">
            <?php  require_once('../App/Views/_layouts/_nav.php') ?>
            <?php  require_once('../App/Views/_layouts/_header.php') ?>
            <?php  require_once('../App/Views/_layouts/_search.php') ?>
            <?php  require_once('../App/Views/_layouts/_messages.php') ?>
