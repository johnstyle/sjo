<?php

use sJo\Controller\Component\Component;

if(Component::getInstance()->alert->exists()): ?>
    <?php foreach (Component::getInstance()->alert->get() as $type => $alerts): ?>
        <div class="alert alert-<?php echo $type; ?>">
        <?php if(count($alerts) > 1): ?>
            <ol>
            <?php foreach ($alerts as $alert): ?>
                <li><?php echo $alert; ?></li>
            <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <?php foreach ($alerts as $alert): ?>
                <p><?php echo $alert; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>