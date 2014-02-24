<?php if($this->type != 'hidden'): ?>
<div class="form-group">
    <?php if($this->label): ?>
        <label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label>
    <?php endif; ?>
<?php endif; ?>
    <input
        type="<?php echo $this->type; ?>"
        name="<?php echo $this->name; ?>"
        id="<?php echo $this->id; ?>"
        class="form-control <?php echo $this->class; ?>"
        <?php if($this->label): ?>
            placeholder="<?php echo $this->placeholder; ?>"
        <?php endif; ?>
        value="<?php echo $this->value; ?>"
        <?php if($this->autofocus): ?>
            autofocus
        <?php endif; ?>
        />
<?php if($this->type != 'hidden'): ?>
</div>
<?php endif; ?>