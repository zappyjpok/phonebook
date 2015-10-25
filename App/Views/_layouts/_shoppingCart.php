<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 9/09/2015
 * Time: 9:43 PM
 */ ?>

<div class="row">
    <div class="medium-6 columns jumbo">
        <a href="<?php echo Links::action_link('home/cart') ?>" class="button info cart">
            <img src="<?php echo Links::action_link('images/shopping_cart.png') ?>" alt="shopping cart" />
            <span id="cart"> <?php echo $cart ?>  </span>
        </a>
    </div>
    <div class="medium-6 columns jumbo">
            <?php
            if($startCart !== null)
            { ?>
                <br /> You started shopping <span id="start"> <?php echo $startCart ?> </span> minutes ago
                <br /> You last updated your order <span id="update"> <?php echo $updateCart ?> </span> minutes ago
            <?php  } ?>
    </div>
</div>