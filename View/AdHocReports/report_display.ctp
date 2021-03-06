<?php
/**
 * Copyright (c) 2013 TribeHR Corp - http://tribehr.com
 * Copyright (c) 2012 Luis E. S. Dias - www.smartbyte.com.br
 * 
 * Licensed under The MIT License. See LICENSE file for details.
 * Redistributions of files must retain the above copyright notice.
 */
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
    $columns = 0;
    ?>     
    <?php if (!empty($reportData)):?>
    <table cellpadding = "0" cellspacing = "0" class="report" width="<?php echo $tableWidth;?>">
        <tr class="header">
                <?php foreach ($fieldList as $field): ?>
                <th>
                <?php
                $columns++;
                $displayField = substr($field, strpos($field, '.')+1);
                $displayField = str_replace('_', ' ', $displayField);
                $displayField = ucfirst($displayField);
                echo $displayField; 
                ?>
                </th>
                <?php endforeach; ?>
        </tr>
        <?php 
        $i = 0;        
        foreach ($reportData as $reportItem): 
            $counter++;
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' altrow';
            } 
        ?>
            <tr class="body<?php echo $class;?>">
                <?php foreach ($fieldList as $field): ?>
                    <td>
                    <?php                     
                    $params = explode('.',$field);
                    if ( $fieldsType[$field] == 'float') {
                        echo h($reportItem[$params[0]][$params[1]]);
                    }                        
                    else
                        echo h($reportItem[$params[0]][$params[1]]);
                    ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if ( $showRecordCounter ) { ?>    
        <div class="counter">Total Records: <?php echo $counter;?></div>
    <?php } ?>
    <div class="timestamp"><?php echo __('Report Generated') . ' : ' . date('Y-m-d H:i:s'); ?></div>
    <?php endif; ?>
</div>
