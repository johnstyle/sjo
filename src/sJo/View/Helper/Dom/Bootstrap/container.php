<<?php echo $this->tagname; ?>
    <?php if($this->class): ?>
        class="<?php echo $this->class; ?>"
    <?php endif; ?>
    >
    <?php foreach($this->elements as $element): ?>
        <?php echo $element; ?>
    <?php endforeach; ?>
</<?php echo $this->tagname; ?>>