<?php if($this->col): ?>
    <div class="col-lg-<?php echo $this->col; ?>">
<?php endif; ?>
    <?php if($this->container): ?>
        <<?php echo $this->container['tagname']; ?>
            <?php foreach($this->container['attr'] as $attr=>$value): ?>
                <?php echo $attr; ?>="<?php echo $value; ?>"
            <?php endforeach; ?>
            >
    <?php endif; ?>
        <div class="panel panel-<?php echo $this->type; ?>">
            <?php if($this->title): ?>
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $this->title; ?></h3>
                </div>
            <?php endif; ?>
            <?php if($this->elements): ?>
                <?php foreach($this->elements as $element): ?>
                    <?php if(1 == 'Table'): ?>
                        <?php echo $element; ?>
                    <?php else: ?>
                        <div class="panel-body">
                            <?php echo $element; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if($this->footer): ?>
                <div class="panel-footer clearfix">
                    <?php echo $this->footer; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php if($this->container): ?>
        </<?php echo $this->container['tagname']; ?>>
    <?php endif; ?>
<?php if($this->col): ?>
    </div>
<?php endif; ?>