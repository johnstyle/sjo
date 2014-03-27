<div class="form-group">
    <?php if($this->label): ?>
        <label
            <?php if($this->id): ?>
                for="<?php echo $this->id; ?>"
            <?php endif; ?>
            ><?php echo $this->label; ?></label>
    <?php endif; ?>
    <select
        name="<?php echo $this->name; ?>"
        <?php if($this->id): ?>
            id="<?php echo $this->id; ?>"
        <?php endif; ?>
        class="form-control <?php echo $this->class; ?>"
        <?php if($this->autofocus): ?>
            autofocus
        <?php endif; ?>
        >
        <?php foreach ($this->options as $value=>$option): ?>
            <option
                value="<?php echo $value; ?>"
                <?php if ($value == $this->value): ?>
                    selected="selected"
                <?php endif; ?>
                >
                <?php echo $option; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>