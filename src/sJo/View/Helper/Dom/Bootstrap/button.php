<button
    type="<?php echo $this->type; ?>"
    class="btn btn-primary <?php echo $this->class; ?>"
    <?php if($this->name): ?>
        name="<?php echo $this->name; ?>"
    <?php endif; ?>
    >
    <?php echo $this->value; ?>
</button>