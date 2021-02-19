<?php

namespace A3020\PageFeedback\Listener;

use A3020\PageFeedback\Entity\Form;
use A3020\PageFeedback\Event\PageFeedbackOutput;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PageOutput
{
    /** @var Form */
    protected $form;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\GenericEvent $event
     * @param Form $form
     */
    public function handle($event, Form $form)
    {
        $this->form = $form;

        $contents = $this->addButton(
            $event->getArgument('contents')
        );

        $event->setArgument('contents', $contents);
    }

    /**
     * Add a trigger / button to the page to open the feedback dialog
     *
     * Use the 'on_page_feedback_output' event if you'd like to change
     * the dialog, trigger, or button.
     *
     * @param string $contents
     *
     * @return string
     */
    private function addButton($contents)
    {
        $code = '<div class="page-feedback">';
        $code .= '<a href="' . $this->getUrl() . '" class="page-feedback-button">' . e($this->getButtonCaption()) . '</a>';
        $code .= '</div>';

        $code .= "<script>$('.page-feedback-button').magnificPopup({
            type: 'ajax',
            focus: '.focus-when-open',
            closeOnBgClick: false,
            showCloseBtn: true,
            enableEscapeKey: false
        });</script>";

        $event = new PageFeedbackOutput();
        $event->setCode($code);

        $this->dispatcher->dispatch('on_page_feedback_output', $event);

        return str_replace('</body>', $event->getCode() . '</body>', $contents);
    }

    /**
     * @return \League\URL\URLInterface
     */
    private function getUrl()
    {
        $page = Page::getCurrentPage();

        return Url::to('/ccm/page_feedback/form?cid=' . $page->getCollectionID());
    }

    /**
     * @return string
     */
    private function getButtonCaption()
    {
        if (!empty($this->form->getButtonCaption())) {
            return $this->form->getButtonCaption();
        }

        return t('Feedback?');
    }
}
