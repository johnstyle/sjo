<div class="form-group">
    <?php if($this->label): ?>
        <label
            <?php if($this->id): ?>
                for="<?php echo $this->id; ?>"
            <?php endif; ?>
            ><?php echo $this->label; ?></label>
    <?php endif; ?>
    <select<?php echo $this->attributes(); ?>>
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