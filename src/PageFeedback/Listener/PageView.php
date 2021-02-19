<?php

namespace A3020\PageFeedback\Listener;

use A3020\PageFeedback\Entity\Form;
use A3020\PageFeedback\FormMatcher;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Captcha\CaptchaInterface;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Event;
use Concrete\Core\Page\Page;
use Concrete\Core\View\View;

class PageView implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FormMatcher
     */
    private $formMatcher;

    /**
     * @var CaptchaInterface
     */
    private $captcha;

    public function __construct(Repository $config, Request $request, FormMatcher $formMatcher, CaptchaInterface $captcha)
    {
        $this->config = $config;
        $this->request = $request;
        $this->formMatcher = $formMatcher;
        $this->captcha = $captcha;
    }

    public function handle(Event $event)
    {
        if ($this->disableForCurrentRequest($event->getPageObject())) {
            return;
        }

        $form = $this->findForm($event->getPageObject());

        // No form matches with this page
        if (!$form) {
            return;
        }

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
        $dispatcher = $this->app['director'];

        $dispatcher->addListener('on_page_output', function ($event) use ($form) {
            /** @var PageOutput $listener */
            $listener = $this->app->make(PageOutput::class);
            $listener->handle($event, $form);
        });

        $al = AssetList::getInstance();
        $view = View::getInstance();

        $view->requireAsset('core/lightbox');

        $style = $this->config->get('page_feedback.style', 'generic');

        if (!empty($style)) {
            $al->register('css', 'page_feedback/style', 'css/style-' . $style . '.css', [], 'page_feedback');
            $view->requireAsset('css', 'page_feedback/style');
        }

        if ($form->isCaptchaEnabled()) {
            $this->loadCaptcha();
        }
    }

    /**
     * Should Page Feedback be disabled for the current request?
     *
     * @param \Concrete\Core\Page\Page $page
     *
     * @return bool
     */
    private function disableForCurrentRequest(Page $page)
    {
        // Disable in admin area
        if ($page->isAdminArea()) {
            return true;
        }

        // Disable in edit mode
        if ($page->isEditMode()) {
            return true;
        }

        // Disable for AJAX requests
        if ($this->request->isXmlHttpRequest()) {
            return true;
        }

        return false;
    }

    /**
     * Get an active form for the current page
     *
     * @param \Concrete\Core\Page\Page $page
     *
     * @return Form|null
     */
    private function findForm(Page $page)
    {
        return $this->formMatcher->findByPage($page);
    }

    /**
     * Load the Captcha JS libraries upfront, if needed.
     *
     * Because the form is loaded via AJAX, the reCAPTCHA
     * add-on causes problems. That's why the JS assets are loaded
     * when the page is viewed. When the form is shown, the
     * captcha is triggered manually.
     */
    private function loadCaptcha()
    {
        ob_start();
        $this->captcha->showInput();
        ob_end_clean();
    }
}