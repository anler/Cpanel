<div class="menu-sections index">

<?php echo $cpanel->newSectionLink(__('New Section', true), array('class' => 'new')) ?>

<?php echo $tree->generate($sections, array('element' => 'menu_sections', 'class' => 'data-tree', 'type' => 'ol')) ?>

</div>