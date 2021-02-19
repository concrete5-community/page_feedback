<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \A3020\PageFeedback\Entity\Form[] $forms */
/** @var string $administratorEmail */
?>

<div class="ccm-dashboard-header-buttons btn-group">
    <a class="btn btn-primary" href="<?php echo $this->action('add'); ?>">
        <?php echo t('Add feedback form'); ?>
    </a>
</div>

<div class="ccm-dashboard-content-inner">
    <?php
    if (count($forms) === 0) {
        echo '<p>' . t('No forms have been found.') . '</p>';
    } else {
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('Name') ?></th>
                    <th><?php echo t('Type') ?></th>
                    <th><?php echo t('Is enabled') ?></th>
                    <th><?php echo t('Recipient') ?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($forms as $form) {
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo $this->action('edit', $form->getId()); ?>">
                                <?php echo e($form->getName()); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo e(ucfirst($form->getType())); ?>
                        </td>
                        <td>
                            <?php
                            echo $form->isEnabled()
                                ? t('Yes')
                                : t('No');
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $form->getEmailRecipient()
                                ? $form->getEmailRecipient()
                                : t('Administrator') . ' (' . $administratorEmail . ')';
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-default btn-xs" href="<?php echo $this->action('edit', $form->getId()); ?>">
                                <?php echo t('Edit'); ?>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>
