<button
    type="<?php echo $this->type; ?>"
    class="btn btn-<?php echo $this->color; ?> <?php echo $this->class; ?>"
    <?php if($this->name): ?>
        name="<?php echo $this->name; ?>"
    <?php endif; ?>
    >
    <?php echo $this->value; ?>
</button>