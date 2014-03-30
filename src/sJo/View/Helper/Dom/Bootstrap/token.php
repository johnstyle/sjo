<?php foreach($this->elements as $name=>$value): ?>
    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
<?php endforeach; ?>