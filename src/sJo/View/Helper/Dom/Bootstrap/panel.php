<?php if(self::$view->col): ?>
<div class="col-lg-<?php echo self::$view->col; ?>">
<?php endif; ?>
    <form method="post" action="">
        <div class="panel panel-<?php echo self::$view->type; ?>">
            <?php if(self::$view->title): ?>
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo self::$view->title; ?></h3>
                </div>
            <?php endif; ?>
            <?php if(self::$view->elements): ?>
                <div class="panel-body">
                    <?php foreach(self::$view->elements as $element): ?>
                        <?php echo $element; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(self::$view->footer): ?>
                <div class="panel-footer clearfix">
                    <?php echo self::$view->footer; ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
<?php if(self::$view->col): ?>
</div>
<?php endif; ?>