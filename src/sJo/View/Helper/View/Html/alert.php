<?php

use sJo\Loader\Alert;

if(Alert::exists($this->formHash)): ?>
    <?php foreach (Alert::get($this->formHash) as $type => $alerts): ?>
        <div class="alert alert-<?php echo $type; ?> clearfix" data-dismiss="alert">
            <?php
                switch($type) {
                    case 'success':
                        $icon = 'ok-sign';
                        break;
                    case 'danger':
                        $icon = 'remove-sign';
                        break;
                    case 'warning':
                        $icon = 'warning-sign';
                        break;
                    case 'info':
                        $icon = 'info-sign';
                        break;
                    default:
                        $icon = false;
                        break;
                }
            ?>
            <?php if($icon): ?>
                <span
                    class="pull-left glyphicon glyphicon-<?php echo $icon; ?>"
                    style="line-height:1.4;margin-right:10px"
                    ></span>
            <?php endif; ?>
            <?php if(count($alerts) > 1): ?>
                <ol class="pull-left">
                <?php foreach ($alerts as $alert): ?>
                    <li><?php echo $alert; ?></li>
                <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <?php foreach ($alerts as $alert): ?>
                    <p class="pull-left"><?php echo $alert; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>