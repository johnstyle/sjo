<?php

use sJo\Loader\Router;

?>
<<?php echo $this->container; ?>>
    <ul class="nav <?php echo $this->container == 'aside' ? 'nav-sidebar' : 'navbar-nav'; ?><?php if(isset($this->pull)): ?> navbar-<?php echo $this->pull; ?><?php endif; ?>">
        <?php foreach($this->elements as $element): ?>
            <li>
                <a<?php $this->attributes(); ?>
                    <?php if($element['tooltip']): ?>
                        data-toggle="tooltip"
                        <?php if($element['tooltip']): ?>
                            data-placement="<?php $element['tooltip']['placement']; ?>"
                        <?php endif; ?>
                        title="<?php isset($element['tooltip']['title']) ? $element['tooltip']['title'] : $element['title']; ?>"
                    <?php endif; ?>
                    >
                    <?php if($element['icon']): ?>
                        <span class="glyphicon glyphicon-<?php echo $element['icon']; ?>"></span>
                    <?php endif; ?>
                    <span class="title"><?php echo $element['title']; ?></span>
                </a>
                <?php if($element['children']): ?>
                    <ul class="nav">
                        <?php foreach($element['children'] as $child): ?>
                            <li>
                                <a<?php $this->attributes(); ?>
                                    <?php if($child['link']): ?>
                                        href="<?php echo $child['link']; ?>"
                                    <?php endif; ?>
                                    <?php if($child['tooltip']): ?>
                                        data-toggle="tooltip"
                                        <?php if($child['tooltip']): ?>
                                            data-placement="<?php $child['tooltip']['placement']; ?>"
                                        <?php endif; ?>
                                        title="<?php isset($child['tooltip']['title']) ? $child['tooltip']['title'] : $child['title']; ?>"
                                    <?php endif; ?>
                                    >
                                    <?php if($child['icon']): ?>
                                        <span class="glyphicon glyphicon-<?php echo $child['icon']; ?>"></span>
                                    <?php endif; ?>
                                    <span class="title"><?php echo $child['title']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</<?php echo $this->container; ?>>