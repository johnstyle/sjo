<table<?php echo $this->attributes(); ?>>
    <?php if($this->thead): ?>
        <thead>
        <tr>
        <?php foreach($this->thead as $thead): ?>
            <th
                <?php if($thead['align']): ?>
                    class="text-<?php echo $thead['align']; ?>"
                <?php endif; ?>
                ><?php echo $thead['label']; ?></th>
        <?php endforeach; ?>
        </tr>
        </thead>
    <?php endif; ?>
    <?php if($this->tfoot): ?>
        <tfoot>
        <tr>
            <?php foreach($this->tfoot as $tfoot): ?>
                <td><?php echo $tfoot; ?></td>
            <?php endforeach; ?>
        </tr>
        </tfoot>
    <?php endif; ?>
    <?php if($this->tbody): ?>
        <tbody>
        <?php foreach($this->tbody as $items): ?>
        <tr>
            <?php foreach($this->thead as $name=>$thead): ?>
                <td
                    <?php if($thead['align']): ?>
                        class="text-<?php echo $thead['align']; ?>"
                    <?php endif; ?>
                    ><?php echo is_array($items) ?
                        (isset($items[$name]) ? $items[$name] : '-')  :
                        (isset($items->{$name}) ? $items->{$name} : '-');
                    ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
</table>