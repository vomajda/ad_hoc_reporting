<?php
/**
 * Copyright (c) 2013 TribeHR Corp - http://tribehr.com
 * Copyright (c) 2012 Luis E. S. Dias - www.smartbyte.com.br
 *
 * Licensed under The MIT License. See LICENSE file for details.
 * Redistributions of files must retain the above copyright notice.
 */
echo $this->Html->script(array('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'));
?>

<div class="reportDetails">
	<?php
	// @todo - move this TribeHR-specific logo image out of this plugin
	?>
	<?php echo $this->Html->image('tribehr_logo.png', array('class'=>'logo')); ?>
	<h1><?php echo ($reportName == '' ? 'Ad-Hoc Report' : h($reportName));?></h1>
	<h2><?php echo h($settings['Config']['name']); ?></h2>
	<div class="timestamp">Report Generated : <strong><?php echo date('Y-m-d H:i:s'); ?></strong></div>
</div>

<div class="reportTable">
    <?php
    $counter = 0;

    if (!empty($reportData)) { ?>
    <table cellpadding = "0" cellspacing = "0" class="report" width="<?php echo $tableWidth;?>">
        <tr class="header">
            <?php foreach ($fieldList as $field) {
				$displayField = substr($field, strpos($field, '.')+1);
				$displayField = str_replace('_', ' ', $displayField);
				$displayField = ucfirst($displayField);

				// minimum column width is either 50 or (length * 9) to prevent label overflow
                $minWidth = 50;
				$columnWidth = max(array($tableColumnWidth[$field], $minWidth, strlen($displayField) * 8));
				// force ID cols to be 50 despite they're not configured like that in DB
                $columnWidth = strtolower($displayField) == 'id' ?  $minWidth : $columnWidth;
            ?>
                <th width="<?php echo $columnWidth; ?>"><?php echo $displayField; ?></th>
            <?php } ?>
        </tr>
        <?php
        foreach ($reportData as $reportItem) {
            $class = null;
            if ($counter++ % 2 == 0) {
                $class = ' altrow';
            }
        ?>
            <tr class="body<?php echo $class;?>">
                <?php foreach ($fieldList as $field) {
                    $params = explode('.',$field);
                    $value = h($reportItem[$params[0]][$params[1]]);
                ?>
                    <td><?php echo $value; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
    <?php if ( $showRecordCounter ) { ?>
        <div class="counter">Total Records: <?php echo $counter;?></div>
    <?php } ?>
    <div class="timestamp"><?php echo __('Report Generated') . ' : ' . date('Y-m-d H:i:s'); ?></div>
    <?php } ?>
</div>

<script>
    $(function() {
        // toggle text wrapping on hover to see hidden overflown content
        $('tr.body td').hover(
            function() { $( this ).css('white-space', 'normal'); },
            function() { $( this ).css('white-space', 'nowrap'); }
        );

        /* Various functions */
        var fn = {
            /**
             * Returns if given element is empty
             * @param elem jQuery element object
             * @returns {boolean}
             */
            isEmpty: function(elem) {
                return !fn.getTrimmedContent(elem);
            },
            /**
             * Return minimum cell width which is either 50px or length * 8 (to prevent overflowing)
             * @param elem jQuery element object
             * @returns {number}
             */
            getMinWidth: function(elem) {
                return Math.max(50, fn.getTrimmedContent(elem).length * 8);
            },
            /**
             * Return trimmed element content
             * @param elem jQuery element object
             * @returns {string}
             */
            getTrimmedContent: function(elem) {
                return $.trim(elem.html());
            }
        },

        /* Logic related to auto-shrinking empty columns */
        shrinkEmptyColumns = {
            table: null,
            /**
             * Return a column (set of cells) by given index
             * @param index Number
             * @returns {Array}
             */
            getColumnByIndex: function(index) {
                var cells = [];
                $(shrinkEmptyColumns.table + 'tr.body').each(function(i, v) {
                    cells.push($(this).find('td').eq(index));
                });
                return cells;
            },
            /* Check if given column is empty and if so shrink the header cell */
            processColumn: function(index) {
                var header = $(shrinkEmptyColumns.table + 'tr.header th').eq(index),
                    cells = shrinkEmptyColumns.getColumnByIndex(index),
                    shrink = true;

                $.each(cells, function(i, v) {
                    if (!fn.isEmpty($(this))) {
                        return shrink = false;
                    }
                });

                if (shrink) {
                    header.attr('width', fn.getMinWidth(header));
                }
            },
            /* For given table process column by column */
            processTable: function(table) {
                shrinkEmptyColumns.table = table + ' ';
                var i, cols = $(shrinkEmptyColumns.table + 'tr:first > th').length;
                for (i = 0; i < cols; i++) {
                    shrinkEmptyColumns.processColumn(i);
                };
            },
            go: function() {
                shrinkEmptyColumns.processTable('table.report');
            }
        };

        // shrink the empty columns!
        shrinkEmptyColumns.go();
    });
</script>
