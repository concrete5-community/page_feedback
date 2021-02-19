<?php
defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var bool $isEnabled */
/** @var string $style */
?>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        echo $token->output('a3020.page_feedback.settings');
        ?>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t('If disabled, %s will be completely turned off.', t('Page Feedback')) ?>"
                   for="isEnabled">
                <?php
                echo $form->checkbox('isEnabled', 1, $isEnabled);
                ?>
                <?php echo t(/*i18n: where %s is the name of the add-on */'Enable %s', t('Page Feedback')); ?>
            </label>
        </div>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t('This controls if and which a stylesheet should be loaded to style the button and form.') ?>"
                   for="style">
                <?php echo t('Load stylesheet'); ?>
            </label>

            <?php
            echo $form->select('style', [
                '' => t('None'),
                'generic' => t('Generic'),
                'bootstrap' => t('Bootstrap'),
            ], $style);
            ?>
        </div>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button class="pull-right btn btn-primary" type="submit"><?php echo t('Save') ?></button>
            </div>
        </div>
    </form>
</div>
