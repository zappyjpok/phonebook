<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 25/10/2015
 * Time: 4:57 PM
 */?>


    <div class="carousel slide" data-ride="carousel" id="featured">

        <div class="carousel-inner">
            <div class="item active">
                <img src="<?php echo Links::action_link('images/Banner/MainBanner.jpg') ?>" alt="Stay in touch with friends">
            </div>
            <div class="item">
                <img src="<?php echo Links::action_link('images/Banner/Aboutus.jpg') ?>" alt="Learn how to never loose a phone number again!">
            </div>
            <div class="item">
                <img src="<?php echo Links::action_link('images/Banner/register.jpg') ?>" alt="Lets get started">
            </div>
        </div> <!-- Close Carousel-inner -->

        <a class="left carousel-control" href="#featured" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"> </span>
        </a>
        <a class="right carousel-control" href="#featured" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"> </span>
        </a>
    </div> <!-- Close Carousel -->


