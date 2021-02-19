<?php

namespace Concrete\Package\PageFeedback\Controller\SinglePage\Dashboard\System\PageFeedback;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Settings extends DashboardPageController
{
    public function view()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->set('isEnabled', (bool) $config->get('page_feedback.enabled', true));
        $this->set('style', $config->get('page_feedback.style', 'generic'));
    }

    public function save()
    {
        if (!$this->token->validate('a3020.page_feedback.settings')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/system/page_feedback/settings');
        }

        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        $config->save('page_feedback.enabled', (bool) $this->post('isEnabled'));
        $config->save('page_feedback.style', $this->post('style', 'generic'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to('/dashboard/system/page_feedback/settings');
    }
}
