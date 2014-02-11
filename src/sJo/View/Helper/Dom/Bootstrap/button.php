<button
    type="<?php echo self::$view->type; ?>"
    class="btn btn-primary <?php echo self::$view->class; ?>"
    <?php if(self::$view->name): ?>
        name="<?php echo self::$view->name; ?>"
    <?php endif; ?>
    >
    <?php echo self::$view->value; ?>
</button>