<fieldset>
    <?php if($this->legend): ?>
        <legend><?php echo $this->legend; ?></legend>
    <?php endif; ?>
    <?php if($this->elements): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</fieldset>