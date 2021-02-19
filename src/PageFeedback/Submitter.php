<?php

namespace A3020\PageFeedback;

use A3020\PageFeedback\Entity\Form;
use A3020\PageFeedback\Store\Handler;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\IPService;
use Concrete\Core\Validation\CSRF\Token;
use Exception;

class Submitter implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FormMatcher
     */
    private $formMatcher;

    /**
     * @var Token
     */
    private $token;
    /**
     * @var IPService
     */
    private $ipService;

    /**
     * @param Request $request
     * @param FormMatcher $formMatcher
     * @param Token $token
     *
     * @throws Exception
     */
    public function __construct(
        Request $request,
        FormMatcher $formMatcher,
        Token $token
    )
    {
        $this->request = $request;
        $this->formMatcher = $formMatcher;
        $this->token = $token;

        if (!$this->token->validate('a3020.page_feedback.form.submit')) {
            throw new Exception($this->token->getErrorMessage());
        }

        $this->page = Page::getByID($this->request->get('cid'));
        if (!$this->page || $this->page->isError()) {
            throw new Exception('Invalid page');
        }

        $form = $this->formMatcher->findByPage($this->page);
        if (!$form) {
            throw new Exception('No associated form found');
        }

        $this->form = $form;
    }

    /**
     * @throws Exception
     */
    public function submit()
    {
        $this->getHandler($this->form)
            ->setRequest($this->request)
            ->setPage($this->page)
            ->setForm($this->form)
            ->store();
    }

    /**
     * Get handler object
     *
     * Each form type has its own implementation of storing a submission
     *
     * @param Form $form
     * @throws Exception
     *
     * @return \A3020\PageFeedback\Store\Handler
     */
    private function getHandler(Form $form)
    {
        $class = ucfirst($form->getType());
        $fqn = '\\A3020\\PageFeedback\\Store\\Handler\\' . $class;

        if (!class_exists($fqn)) {
            throw new Exception("Class does not exist: " . $class);
        }

        return $this->app->make($fqn);
    }
}
