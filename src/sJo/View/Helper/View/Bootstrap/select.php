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