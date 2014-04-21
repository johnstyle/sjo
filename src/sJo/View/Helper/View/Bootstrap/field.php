<?php if($this->attribute('type') != 'hidden'): ?>
    <div class="form-group
        <?php if($this->group['class']): echo $this->group['class']; endif; ?>
        <?php if($this->alert): echo 'has-' . $this->alert . ' has-feedback'; endif; ?>">
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
    <?php if($this->icon): ?>
        <span class="glyphicon glyphicon-<?php echo $this->icon; ?> <?php if($this->alert): ?>form-control-feedback<?php endif; ?>"></span>
    <?php endif; ?>
<?php if($this->attribute('type') != 'hidden'): ?>
    </div>
<?php endif; ?>