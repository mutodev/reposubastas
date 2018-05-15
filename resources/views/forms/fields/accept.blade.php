<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showField): ?>
    <?= Form::checkbox($name, $options['value'], @$options['checked']) ?>
<?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <label style="display: inline" class="control-label">{!! $options['label'] !!}</label>
<?php endif; ?>

    <?php if ($showError && isset($errors)): ?>
    <?php foreach ($errors->get($nameKey) as $err): ?>
    <div <?= $options['errorAttrs'] ?>><?= $err ?></div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
