<form
    <?php if($this->method): ?>
        method="<?php echo $this->method; ?>"
    <?php endif; ?>
    <?php if($this->action): ?>
        action="<?php echo $this->action; ?>"
    <?php endif; ?>
    <?php if($this->class): ?>
        class="<?php echo $this->class; ?>"
    <?php endif; ?>
    >
    <?php if($this->elements): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</form>