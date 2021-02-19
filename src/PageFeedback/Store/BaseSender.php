<?php

namespace A3020\PageFeedback\Store;

use A3020\PageFeedback\Entity\Form;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Http\Request;
use Concrete\Core\Mail\Service;
use Concrete\Core\Page\Page;
use Exception;

abstract class BaseSender implements Handler, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Service
     */
    protected $mailService;

    /**
     * @var \A3020\PageFeedback\Service
     */
    private $service;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Form
     */
    protected $form;

    public function __construct(
        Service $mailService,
        \A3020\PageFeedback\Service $service
    )
    {
        $this->mailService = $mailService;
        $this->service = $service;
    }

    protected function getEmailRecipient(Form $form)
    {
        if ($form->getEmailRecipient()) {
            return $form->getEmailRecipient();
        }

        return $this->service->getAdministratorEmail();
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function setPage(Page $page)
    {
        $this->page = $page;

        return $this;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @param string $contents
     *
     * @throws Exception
     */
    protected function checkSpam($contents)
    {
        if ($this->form->isCaptchaEnabled()) {
            /** @var \Concrete\Core\Captcha\CaptchaInterface::class $captcha */
            $captcha = $this->app->make('helper/validation/captcha');
            if (!$captcha->check()) {
                throw new Exception(t('Incorrect captcha code'));
            }
        }

        /** @var \Concrete\Core\Permission\IPService $ipService */
        $ipService = $this->app->make('ip');

        /** @var \Concrete\Core\Antispam\Service $antispam */
        $antispam = $this->app->make('helper/validation/antispam');

        // Check if visitor's IP is blacklisted / banned
        if ($ipService->isBlacklisted()) {
            throw new Exception($ipService->getErrorMessage());
        }

        // If an anti spam library is installed, we'll use that to detect spam
        if (!$antispam->check($contents, 'page_feedback')) {
            throw new Exception(t('Feedback is not sent because spam is detected.'));
        }
    }

    /**
     * The submitted form data
     *
     * The string can be sent to an anti-spam library
     *
     * @return string
     */
    protected function getSubmittedData()
    {
        return '';
    }
}
