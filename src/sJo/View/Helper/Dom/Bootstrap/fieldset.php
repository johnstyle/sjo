<fieldset>
    <?php if(self::$view->legend): ?>
        <legend><?php echo self::$view->legend; ?></legend>
    <?php endif; ?>
    <?php if(self::$view->elements): ?>
        <?php foreach(self::$view->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</fieldset>