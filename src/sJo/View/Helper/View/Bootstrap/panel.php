<<?php echo $this->tagname . $this->attributes(); ?>>
    <?php if($this->header): ?>
        <div class="panel-heading">
            <?php foreach($this->header as $element): ?>
                <?php echo $element; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if($this->main): ?>
        <div class="panel-body">
            <?php foreach($this->main as $element): ?>
                <?php echo $element; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if($this->footer): ?>
        <div class="panel-footer clearfix">
            <?php foreach($this->footer as $element): ?>
                <?php echo $element; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</<?php echo $this->tagname; ?>>