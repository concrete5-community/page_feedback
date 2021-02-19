<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;
?>
<p><?php echo t('Congratulations, the add-on has been installed!'); ?></p>
<br>

<p>
    <?php
    echo t('To complete the installation, please configure %s.', t('Page Feedback'));
    ?>
</p><br>

<a class="btn btn-primary" href="<?php echo Url::to('/dashboard/system/page_feedback/forms/add') ?>">
    <?php
    echo t('Start by adding a feedback form');
    ?>
</a>
