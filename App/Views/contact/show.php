
<?php require_once('../App/Views/_layouts/_master.php') ?>

<div class="panel panel-default">
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo Output::phpOutput(Links::action_link($data['main'])); ?>"
                 alt="<?php echo Output::phpOutput($data['contact']['firstName']) . ' ' . Output::phpOutput($data['contact']['lastName']) ?>"/>
        </div>
        <div class="col-md-4">
            <h5> Email: <?php echo Output::phpOutput($data['contact']['email']) ?></h5>
            <br>
            <h5> Phone: <?php echo Output::phpOutput($data['contact']['phone']) ?> </h5>
        </div>

        <?php require_once('../App/Views/_layouts/_ads.php') ?>
    </div>
</div>

<?php require_once('../App/Views/_layouts/_footer.php') ?>