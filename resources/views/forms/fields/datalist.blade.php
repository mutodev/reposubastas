<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

    <?php if ($showField): ?>
    <?php $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null; ?>
    <?= Form::input($type, $name, $options['value'], array_replace($options['attr'], ['list' => 'list'])) ?>
    <?= str_replace('form-control', '', str_replace('select', 'datalist', Form::select($name, (array)$emptyVal + $options['choices'], $options['selected'], array_replace($options['attr'], ['id' => 'list'])))) ?>
<?php endif; ?>
    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
