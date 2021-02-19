<?php
defined('C5_EXECUTE') or die('Access Denied.');

/** @var array $typeOptions */
/** @var \A3020\PageFeedback\Entity\Form $entity */
/** @var string $administratorEmail */
/** @var bool $isEcRecaptchaInstalled */
/** @var \Concrete\Core\Editor\EditorInterface $editor */

if ($entity->getId()) {
    ?>
    <div class="ccm-dashboard-header-buttons btn-group">
        <a class="btn btn-danger" href="<?php echo $this->action('delete', $entity->getId()); ?>">
            <?php echo t('Delete'); ?>
        </a>
    </div>
    <?php
}
?>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        /** @var \Concrete\Core\Validation\CSRF\Token $token */
        echo $token->output('a3020.page_feedback.forms');

        echo $form->hidden('id', $entity->getId());
        ?>

        <fieldset>
            <legend><?php echo t('Basic settings') ?></legend>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("If disabled, this form won't be displayed.") ?>"
                       for="isEnabled">
                    <?php
                    echo $form->checkbox('isEnabled', 1, $entity->isEnabled());
                    ?>
                    <?php echo t('Enable feedback form'); ?>
                </label>
            </div>

            <div class="form-group">
                <?php
                echo $form->label('name', t('Name') . ' *');
                echo $form->text('name', $entity->getName(), [
                    'required' => 'required',
                    'autofocus' => 'autofocus',
                ]);
                ?>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("By default this text will be shown before the form. Leave empty if you don't want to use an introduction text.") ?>"
                       for="introText">
                    <?php
                    echo t('Content text in feedback dialog');
                    ?>
                </label>

                <?php
                echo $editor->outputStandardEditor('introText', $entity->getIntroText());
                ?>
            </div>

            <div class="form-group">
                <?php
                echo $form->label('emailRecipient', t('Email recipient'));
                echo $form->email('emailRecipient', $entity->getEmailRecipient(), [
                    'placeholder' => t('Leave empty to use the email address of the administrator (%s)', $administratorEmail),
                ]);
                ?>
            </div>

            <div class="form-group">
                <?php
                echo $form->label('buttonCaption', t('Button caption'));
                echo $form->text('buttonCaption', $entity->getButtonCaption(), [
                    'placeholder' => t('Leave empty to use default caption'),
                ]);
                ?>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("If enabled, the visitor could leave an email address.") ?>"
                       for="enableEmailField">
                    <?php
                    echo $form->checkbox('enableEmailField', 1, $entity->isEmailFieldEnabled());
                    ?>
                    <?php echo t('Enable email field'); ?>
                </label>
            </div>

            <div class="form-group toggle-email-is-enabled <?php echo $entity->isEmailFieldEnabled() ? '' : 'hide' ?>">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("If enabled, the visitor should leave an email address.") ?>"
                       for="emailFieldRequired">
                    <?php
                    echo $form->checkbox('emailFieldRequired', 1, $entity->isEmailFieldRequired());
                    ?>
                    <?php echo t('Make email field required'); ?>
                </label>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("If enabled, the visitor needs to 'solve' a captcha field first. Enable this if you are getting spam submissions.") ?>"
                       for="enableCaptcha">
                    <?php
                    echo $form->checkbox('enableCaptcha', 1, $entity->isCaptchaEnabled());
                    ?>
                    <?php echo t('Enable captcha field'); ?>
                </label>

                <?php
                if (!$isEcRecaptchaInstalled) {
                    ?>
                    <small class="help-block">
                        <?php echo t("Looking for a different captcha solution? Try '%s'.",
                            '<a href="https://www.concrete5.org/marketplace/addons/recaptcha" target="_blank">'
                            . t('ExchangeCore reCAPTCHA')
                            . '</a>'
                        ); ?>
                    </small>
                    <?php
                }
                ?>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t('If enabled, the modal will be automatically closed after %s seconds.', 3) ?>"
                       for="enableAutoClose">
                    <?php
                    echo $form->checkbox('enableAutoClose', 1, $entity->isAutoCloseEnabled());
                    ?>
                    <?php echo t('Automatically close modal after feedback is sent'); ?>
                </label>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t('If enabled, the visitor has to agree to the terms of use.') ?>"
                       for="enableAcceptTerms">
                    <?php
                    echo $form->checkbox('enableAcceptTerms', 1, $entity->isAcceptTermsEnabled());
                    ?>
                    <?php echo t('Enable accept terms of use field'); ?>
                </label>
            </div>

            <div class="form-group toggle-accept-terms-is-enabled <?php echo $entity->isAcceptTermsEnabled() ? '' : 'hide' ?>">
                <?php
                echo $form->label('acceptTermsText', t('Text next to the accept terms checkbox'));
                echo $editor->outputStandardEditor('acceptTermsText', $entity->getAcceptTermsText());
                ?>
            </div>
        </fieldset>

        <fieldset>
            <legend><?php echo t('Advanced settings') ?></legend>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("E.g. if you only want to show the form on all webshop pages, you'd use '*/shop/*'.") ?>"
                       for="urlsInclude">
                    <?php echo t('Show this form on URLs that match'); ?>
                </label>

                <?php
                echo $form->text('urlsInclude', $entity->getUrlsInclude(), [
                    'placeholder' => t('Leave empty to show on all pages'),
                ]);
                ?>
            </div>

            <div class="form-group">
                <label class="control-label"
                       for="urlsExclude">
                    <?php echo t('Hide this form on URLs that match'); ?>
                </label>

                <?php
                echo $form->text('urlsExclude', $entity->getUrlsExclude(), [
                    'placeholder' => t('Leave empty to show on all pages'),
                ]);
                ?>
            </div>

            <small class="help-block"><?php echo t('The %sfnmatch%s function is used to match URLs.',
                '<a href="https://secure.php.net/manual/en/function.fnmatch.php" target="_blank">',
                '</a>');
                echo ' ' . t('Examples') . ':<br>';
                echo '<div style="margin-left: 15px; margin-top: 5px;">';
                echo '<code>' . t('*.com/') . '</code> ' . t('To show / hide only on the home page') . '<br>';
                echo '<code>' . t('*/contact') . '</code> ' . t('To show / hide on the contact page(s)') . '<br>';
                echo '<code>' . t('*/webshop/*') . '</code> ' . t('To show / hide on all pages below the webshop page(s)') . '<br>';
                echo '</div>';
                ?>
            </small>
        </fieldset>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <a class="btn btn-default" href="<?php echo $this->action(''); ?>"><?php echo t('Cancel') ?></a>
                <div class="pull-right">
                    <button class="btn" name="save" type="submit"><?php echo t('Save') ?></button>
                    <button class="btn btn-primary" name="saveAndClose" type="submit"><?php echo t('Save and Close') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
   $('#enableEmailField').change(function() {
      $('.toggle-email-is-enabled').toggleClass('hide', !$(this).is(':checked'));
   });

    $('#enableAcceptTerms').change(function() {
        $('.toggle-accept-terms-is-enabled').toggleClass('hide', !$(this).is(':checked'));
    });
});
</script>