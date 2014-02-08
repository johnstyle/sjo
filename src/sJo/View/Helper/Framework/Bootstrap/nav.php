<ul class="nav navbar-nav navbar-right">
    <?php foreach(self::$view->items as $item): ?>
        <li><a href="#"><?php echo $item['title']; ?></a></li>
    <?php endforeach; ?>
</ul>