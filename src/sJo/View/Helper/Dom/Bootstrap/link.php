<a
    <?php if($this->href): ?>
        href="<?php echo $this->href; ?>"
    <?php endif; ?>
    <?php if($this->id): ?>
        id="<?php echo $this->id; ?>"
    <?php endif; ?>
    <?php if($this->class): ?>
        class="<?php echo $this->class; ?>"
    <?php endif; ?>
    <?php if($this->target): ?>
        target="<?php echo $this->target; ?>"
    <?php endif; ?>
    >
    <?php if($this->icon): ?>
        <span class="glyphicon glyphicon-<?php echo $this->icon; ?>"></span>
    <?php endif; ?>
    <?php echo $this->value; ?>
</a>