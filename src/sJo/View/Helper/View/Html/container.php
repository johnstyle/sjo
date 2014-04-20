<?php if($this->tagname): ?>
<<?php echo $this->tagname . $this->attributes(); ?>>
<?php endif; ?>
    <?php if(is_array($this->elements)): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo $this->elements; ?>
    <?php endif; ?>
<?php if($this->tagname): ?>
</<?php echo $this->tagname; ?>>
<?php endif; ?>