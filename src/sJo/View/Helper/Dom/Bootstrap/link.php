<a<?php echo $this->attributes(); ?>>
    <?php if($this->icon): ?>
        <span class="glyphicon glyphicon-<?php echo $this->icon; ?>"></span>
    <?php endif; ?>
    <?php echo $this->value; ?>
</a>