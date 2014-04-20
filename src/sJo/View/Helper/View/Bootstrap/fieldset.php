<<?php echo $this->tagname . $this->attributes(); ?>>
    <?php if($this->legend): ?>
        <legend><?php echo $this->legend; ?></legend>
    <?php endif; ?>
    <?php if(is_array($this->elements)): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo $this->elements; ?>
    <?php endif; ?>
</<?php echo $this->tagname; ?>>