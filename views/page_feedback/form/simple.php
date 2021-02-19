<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;

/**
 * This file can be overridden by copying it to /application/views/page_feedback/form/simple.php
 *
 * @see Also http://dimsemenov.com/plugins/magnific-popup/documentation.html#ajax-type
 */

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \A3020\PageFeedback\Entity\Form $formEntity */
/** @var bool $triggerEcRecaptcha */
/** @var int $cID */
?>

<div class="page-feedback-form">
    <form method="post">
        <?php
        echo $token->output('a3020.page_feedback.form.submit');

        $introText = $formEntity->getIntroTextForDisplay();
        echo $introText ? '<div class="page-feedback-intro-text">' . $introText . '</div>' : '';
        ?>

        <div class="form-group">
            <?php
            echo $form->label('comments', t('Leave your feedback') . ' *');
            echo $form->textarea('comments', null, [
                'class' => 'focus-when-open',
                'required' => 'required',
            ]);
            ?>
        </div>

        <?php
        if ($formEntity->isEmailFieldEnabled()) {
            $label = t('Your email address');
            $options = [];
            if ($formEntity->isEmailFieldRequired()) {
                $options['required'] = 'required';
                $label .= ' *';
            }
            ?>
            <div class="form-group">
                <?php
                echo $form->label('email', $label);
                echo $form->email('email', $defaultEmailAddress, $options);
                ?>
            </div>
            <?php
        }

        if ($formEntity->isCaptchaEnabled()) {
            $app = Application::getFacadeApplication();

            /** @var \Concrete\Core\Captcha\CaptchaInterface $captcha */
            $captcha = $app->make('captcha');
            ?>
            <div class="form-group page-feedback-captcha">
                <?php
                $captchaLabel = $captcha->label();
                if (!empty($captchaLabel)) {
                    ?>
                    <label class="control-label">
                        <?php echo $captchaLabel; ?>
                    </label>
                    <?php
                }
                ?>

                <div><?php $captcha->display(); ?></div>
                <div><?php $captcha->showInput(); ?></div>
            </div>
            <?php
        }

        if ($formEntity->isAcceptTermsEnabled()) {
            ?>
            <div class="form-group page-feedback-terms-of-use">
                <label class="control-label">
                    <?php echo $form->checkbox('terms', 1, null, [
                        'required' => 'required',
                    ]); ?>
                    <?php echo $formEntity->getAcceptTermsTextForDisplay(); ?>
                </label>
            </div>
            <?php
        }
        ?>

        <div class="page-feedback-form-buttons">
            <?php
            echo '<a id="page-feedback-btn-cancel" href="javascript:$.magnificPopup.close();" class="btn btn-default">' . t('Cancel') . '</a>';

            echo $form->submit('page-feedback-btn-send', t('Send'), [
                'class' => 'btn btn-success',
            ]);
            ?>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    var container = $('.page-feedback-form');
    var form = $(container).find('form');

    <?php
    if ($formEntity->isCaptchaEnabled() && $triggerEcRecaptcha) {
        echo 'ecRecaptcha(); ';
    }
    ?>

    $(form).on('submit', function(e) {
        e.preventDefault();

        $.post('<?php echo Url::to('/ccm/page_feedback/submit'); ?>?cid=' + CCM_CID, $(form).serialize())
            .done(function(r) {
                $(container).html(r);

                <?php
                if ($formEntity->isAutoCloseEnabled()) {
                    ?>
                    var closeCaption = $('#page-feedback-btn-close').text();
                    var countDown = 3;
                    var updateButtonCaption = function(count) {
                        $('#page-feedback-btn-close').text(closeCaption + ' (' + count + ')');
                    };

                    updateButtonCaption(countDown);

                    var intervalCountdown = setInterval(function() {
                        countDown--;
                        updateButtonCaption(countDown);
                    }, 1000);

                    setTimeout(function() {
                        clearInterval(intervalCountdown);
                        $.magnificPopup.close();
                    }, 3200);
                    <?php
                }
                ?>
            })
            .error(function(r) {
                alert($.parseJSON(r.responseText).error);
            });

        return false;
    });
});
</script>
