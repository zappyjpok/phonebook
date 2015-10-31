<?php
/**
 * Created by PhpStorm.
 * user: thuyshawn
 * Date: 5/09/2015
 * Time: 10:22 PM
 */ ?>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Phonebook</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Page 1</a></li>
            <li><a href="#">Page 2</a></li>
            <li><a href="#">Page 3</a></li>
          </ul>
          <?php
            if($loggedIn) {
                require_once('../App/Views/_layouts/_navLoggedIn.php');
            } else {
                require_once('../App/Views/_layouts/_navNotLoggedIn.php');
            }
          ?>
        </div>
    </div>
</nav>