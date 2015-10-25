<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 2/09/2015
 * Time: 7:33 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_header.php') ?>

    <div class="row">
        <section class="large-12">
            <h1> Check out our latest deals! </h1>
            <p> Click on any product to get more information </p>
        </section>

        <div>
            <?php
            foreach(array_chunk($data['products'], 3) as $row) { ?>
                <div class="row">
                    <?php
                    foreach($row as $product) { ?>
                        <div class="medium-4 columns">
                            <section class="product-header">
                                <h4>
                                    <a href="<?php echo Links::action_link('home/show/') . $product['proProductID'] ?>">
                                        <?php echo Output::phpOutput($product['proName'])  ?> </a>
                                </h4>
                                <div class="height-200">
                                    <a  class="tool-tip"
                                        data-tip-type="text"
                                        data-tip-source="<?php echo Output::phpOutput(Output::shortenString($product['proDesc'], 75)) . "..."; ?>"
                                        href="<?php echo Links::action_link('home/show/') . $product['proProductID'] ?>">
                                        <img src="<?php echo Links::action_link(Links::changeToThumbnail($product['proImage'])) ?>"
                                            alt="<?php echo Output::phpOutput($product['proName'])  ?>">
                                    </a>

                                </div>
                                <p>
                                    <span class="priceTitle">Price</span>
                                    <span class="price"> $<?php echo Output::phpPrice($product['proPrice'])  ?> </span>
                                </p>
                            </section>
                            <div>
                                <div class="button-group" data-grouptype="OR">
                                    <form class="cart-form" action="<?php echo Links::action_link('home/addToCart') ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $product['proProductID'] ?>">
                                        <input type="submit" class="small button primary radius" value="Add to Cart">
                                        <a href="<?php echo Links::action_link('home/show/') . $product['proProductID'] ?>"
                                           class="small button success radius"> Learn More
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } // end foreach ?>
                </div>
            <?php } // end foreach ?>
        </div>
    </div>
    <div class="row">
        <div id="tooltip_container">

        </div>
    </div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>


