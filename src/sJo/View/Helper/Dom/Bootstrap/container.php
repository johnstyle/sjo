<<?php echo self::$view->tagname; ?>
    <?php if(self::$view->class): ?>
        class="<?php echo self::$view->class; ?>"
    <?php endif; ?>
    >
    <?php foreach(self::$view->elements as $element): ?>
        <?php echo $element; ?>
    <?php endforeach; ?>
</<?php echo self::$view->tagname; ?>>