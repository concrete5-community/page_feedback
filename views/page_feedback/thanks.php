<?php
defined('C5_EXECUTE') or die('Access Denied.');
?>

<form>
    <?php
    echo '<p>' . t('Your feedback has been sent successfully.') . '</p>';
    echo '<div class="page-feedback-form-buttons"><a id="page-feedback-btn-close" href="javascript:$.magnificPopup.close();" class="btn btn-default">' .
        t('Close') .
    '</a></div>';
    ?>
</form>
