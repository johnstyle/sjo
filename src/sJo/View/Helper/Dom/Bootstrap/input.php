<?php if($this->type != 'hidden'): ?>
<div class="form-group">
    <?php if($this->label): ?>
        <label
            <?php if($this->attributes->id): ?>
                for="<?php echo $this->attributes->id; ?>"
            <?php endif; ?>
            ><?php echo $this->label; ?></label>
    <?php endif; ?>
<?php endif; ?>
    <input<?php echo $this->attributes(); ?>/>
<?php if($this->type != 'hidden'): ?>
</div>
<?php endif; ?>