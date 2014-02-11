<<?php echo self::$view->container; ?>>
    <ul class="nav <?php echo self::$view->container == 'aside' ? 'nav-sidebar' : 'navbar-nav'; ?><?php if(isset(self::$view->pull)): ?> navbar-<?php echo self::$view->pull; ?><?php endif; ?>">
        <?php foreach(self::$view->items as $item): ?>
            <li<?php if($item['controller'] == \sJo\Core\Loader::$controller): ?> class="active"<?php endif; ?>>
                <a
                    href="<?php echo $item['controller']; ?>"
                    <?php if($item['tooltip']): ?>
                        data-toggle="tooltip"
                        <?php if($item['tooltip']): ?>
                            data-placement="<?php $item['tooltip']['placement']; ?>"
                        <?php endif; ?>
                        title="<?php isset($item['tooltip']['title']) ? $item['tooltip']['title'] : $item['title']; ?>"
                    <?php endif; ?>
                    >
                    <?php if($item['icon']): ?>
                        <span class="glyphicon glyphicon-<?php echo $item['icon']; ?>>"></span>
                    <?php endif; ?>
                    <span class="title"><?php echo $item['title']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</<?php echo self::$view->container; ?>>