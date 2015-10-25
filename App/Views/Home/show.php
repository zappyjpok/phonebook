<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 27/09/2015
 * Time: 9:15 PM
 */ ?>

<?php require_once('../App/Views/_layouts/_header.php') ?>
    <div class="row">
        <h1> <?php echo $data['product']['proName'] ?> </h1>
        <div class="row">
            <div class="medium-4 columns">
                <img src="<?php echo Links::action_link($data['product']['proImage']) ?>">
            </div>
            <div class="medium-4 columns">
                <p> <?php echo Output::phpOutput($data['product']['proDesc'] ) ?> </p>
                <p> Price: $<?php echo Output::phpPrice($data['product']['proPrice'])?> </p>
            </div>
            <div class="medium-4 columns">
                <fieldset>
                    <legend>Add to Cart</legend>
                    <form action="<?php echo Links::action_link('home/addToCartForm/' . $data['product']['proProductID'] ) ?>" method="post">
                        <div>
                            <div class="medium-6 columns">
                                <label for="Quantity"> Quantity </label>
                                <select name="Quantity" value="1">
                                    <?php
                                    foreach($data['quantity'] as $value) {
                                        echo "<option value=\"$value\">$value</option>";
                                    }
                                    ?>">
                                </select>

                            </div>
                            <div>
                                <input type="submit" class="button" value="Add">
                            </div>
                        </div>

                    </form>
                </fieldset>
            </div>
        </div>
    </div>
<?php require_once('../App/Views/_layouts/_footer.php') ?>