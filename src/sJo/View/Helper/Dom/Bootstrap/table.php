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
            <th
                <?php if($thead['align']): ?>
                    class="text-<?php echo $thead['align']; ?>"
                <?php endif; ?>
                ><?php echo $thead['value']; ?></th>
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
            <?php $i = 0; foreach($items as $item): ?>
                <td
                    <?php if($this->thead[$i]['align']): ?>
                        class="text-<?php echo $this->thead[$i]['align']; ?>"
                    <?php endif; ?>
                    ><?php echo $item; ?></td>
            <?php $i++; endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
</table>