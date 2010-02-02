<div class="menu-sections index">
<?php $cpanel->sectionTitle(__('Menu Sections', true)) ?>

<?php echo $cpanel->newSectionLink(__('New Section', true), array('class' => 'new')) ?>

<?php echo $tree->generate($sections, array('element' => 'menu_sections', 'class' => 'data-tree', 'type' => 'ol')) ?>

<?php echo $cpanel->newSectionLink(__('New Section', true), array('class' => 'new')) ?>

</div>