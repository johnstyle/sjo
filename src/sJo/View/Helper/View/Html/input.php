<?php if (in_array($this->attribute('type'), array('checkbox', 'radio'))) : ?>
    <?php foreach ($this->options as $value=>$option): ?>
        <<?php echo $this->tagname . $this->attributes(); ?>>
        <span><?php echo $option; ?></span>
    <?php endforeach; ?>
<?php else: ?>
    <<?php echo $this->tagname . $this->attributes(); ?>>
<?php endif; ?>
