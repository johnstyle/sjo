<?php if(self::$view->type != 'hidden'): ?>
<div class="form-group">
    <?php if(self::$view->label): ?>
        <label for="<?php echo self::$view->id; ?>"><?php echo self::$view->label; ?></label>
    <?php endif; ?>
<?php endif; ?>
    <input
        type="<?php echo self::$view->type; ?>"
        name="<?php echo self::$view->name; ?>"
        id="<?php echo self::$view->id; ?>"
        class="form-control <?php echo self::$view->class; ?>"
        <?php if(self::$view->label): ?>
            placeholder="<?php echo self::$view->placeholder; ?>"
        <?php endif; ?>
        value="<?php echo self::$view->value; ?>"
        <?php if(self::$view->autofocus): ?>
            autofocus
        <?php endif; ?>
        />
<?php if(self::$view->type != 'hidden'): ?>
</div>
<?php endif; ?>