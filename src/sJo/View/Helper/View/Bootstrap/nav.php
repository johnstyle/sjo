<?php if($this->tagname): ?>
<<?php echo $this->tagname; ?>>
<?php endif; ?>
    <ul<?php echo $this->attributes(); ?>>
        <?php foreach($this->elements as $element): ?>
            <li>
                <?php echo $element; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php if($this->tagname): ?>
</<?php echo $this->tagname; ?>>
<?php endif; ?>