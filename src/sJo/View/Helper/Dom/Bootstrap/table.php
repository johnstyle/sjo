<table
    class="table <?php echo $this->class; ?>"
    <?php if($this->id): ?>
        id="<?php echo $this->id; ?>"
    <?php endif; ?>
    >
    <?php if($this->thead): ?>
        <thead>
        <tr>
        <?php foreach($this->thead as $thead): ?>
            <th><?php echo $thead; ?></th>
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
            <?php foreach($items as $item): ?>
                <td><?php echo $item; ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
</table>