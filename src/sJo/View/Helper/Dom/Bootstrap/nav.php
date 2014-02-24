<?php

use sJo\Loader\Router;

?>
<<?php echo $this->container; ?>>
    <ul class="nav <?php echo $this->container == 'aside' ? 'nav-sidebar' : 'navbar-nav'; ?><?php if(isset($this->pull)): ?> navbar-<?php echo $this->pull; ?><?php endif; ?>">
        <?php foreach($this->elements as $element): ?>
            <li<?php if($element['controller'] == Router::$controller): ?> class="active"<?php endif; ?>>
                <a
                    <?php if($element['link']): ?>
                        href="<?php echo $element['link']; ?>"
                        target="_blank"
                    <?php else: ?>
                        href="<?php echo $element['controller']; ?>"
                    <?php endif; ?>
                    <?php if($element['tooltip']): ?>
                        data-toggle="tooltip"
                        <?php if($element['tooltip']): ?>
                            data-placement="<?php $element['tooltip']['placement']; ?>"
                        <?php endif; ?>
                        title="<?php isset($element['tooltip']['title']) ? $element['tooltip']['title'] : $element['title']; ?>"
                    <?php endif; ?>
                    >
                    <?php if($element['icon']): ?>
                        <span class="glyphicon glyphicon-<?php echo $element['icon']; ?>>"></span>
                    <?php endif; ?>
                    <span class="title"><?php echo $element['title']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</<?php echo $this->container; ?>>