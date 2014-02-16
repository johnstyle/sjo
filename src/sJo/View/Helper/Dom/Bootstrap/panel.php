<?php if(self::$view->col): ?>
    <div class="col-lg-<?php echo self::$view->col; ?>">
<?php endif; ?>
    <?php if(self::$view->container): ?>
        <<?php echo self::$view->container['tagname']; ?>
            <?php foreach(self::$view->container['attr'] as $attr=>$value): ?>
                <?php echo $attr; ?>="<?php echo $value; ?>"
            <?php endforeach; ?>
            >
    <?php endif; ?>
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
    <?php if(self::$view->container): ?>
        </<?php echo self::$view->container['tagname']; ?>>
    <?php endif; ?>
<?php if(self::$view->col): ?>
    </div>
<?php endif; ?>