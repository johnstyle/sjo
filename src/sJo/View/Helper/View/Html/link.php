<a<?php echo $this->attributes(); ?>>
    <?php if($this->icon): ?>
        <span class="glyphicon glyphicon-<?php echo $this->icon; ?>"></span>
    <?php endif; ?>
    <?php if(is_array($this->elements)): ?>
        <?php foreach($this->elements as $element): ?>
            <?php echo $element; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo $this->elements; ?>
    <?php endif; ?>
</a>
<?php if($this->subelements): ?>
    <?php foreach($this->subelements as $subelement): ?>
        <?php echo $subelement; ?>
    <?php endforeach; ?>
<?php endif; ?>