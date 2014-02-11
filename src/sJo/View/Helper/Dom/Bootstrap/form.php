<form
    <?php if(self::$view->method): ?>
        method="<?php echo self::$view->method; ?>"
    <?php endif; ?>
    <?php if(self::$view->action): ?>
        action="<?php echo self::$view->action; ?>"
    <?php endif; ?>
    <?php if(self::$view->class): ?>
        class="<?php echo self::$view->class; ?>"
    <?php endif; ?>
    >
    <?php //self::$Core->Alert->display(); ?>
    <?php if(self::$view->elements): ?>
        <?php foreach(self::$view->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</form>