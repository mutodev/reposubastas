<div class="form-group">

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
    <?php endif; ?>

    <p><?= captcha_img() ?></p>
    <?= Form::input($type, $name, $options['value'], $options['attr']) ?>

    <?php if ($showError && isset($errors)): ?>
    <?php foreach ($errors->get($nameKey) as $err): ?>
    <div <?= $options['errorAttrs'] ?>><?= $err ?></div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
