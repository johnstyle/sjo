<?php if($this->attribute('type') != 'hidden'): ?>
    <div<?php if($this->alert): echo ' class="' . $this->alert . '"'; endif; ?>>
    <?php if($this->label): ?>
        <label
            <?php if($this->attribute('id')): ?>
                for="<?php echo $this->attribute('id'); ?>"
            <?php endif; ?>
            ><?php echo $this->label; ?></label>
    <?php endif; ?>
<?php endif; ?>
    <?php if(is_array($this->elements)): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo $this->elements; ?>
    <?php endif; ?>
<?php if($this->attribute('type') != 'hidden'): ?>
    </div>
<?php endif; ?>