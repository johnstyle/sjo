<div
    class="btn-group<?php if($this->class): ?> <?php echo $this->class; ?><?php endif; ?>"
    >
    <?php foreach($this->elements as $element): ?>
        <?php echo $element; ?>
    <?php endforeach; ?>
</div>