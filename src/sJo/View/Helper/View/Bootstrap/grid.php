<<?php echo $this->tagname . $this->attributes(); ?>>
    <?php if($this->elements): ?>
        <?php foreach($this->elements as $elements): ?>
            <div class="col-lg-<?php echo $elements['grid']; ?>">
                <?php foreach($elements['elements'] as $element): ?>
                    <?php echo $element; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</<?php echo $this->tagname; ?>>