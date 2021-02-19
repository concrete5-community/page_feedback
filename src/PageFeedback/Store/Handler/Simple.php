<?php

namespace A3020\PageFeedback\Store\Handler;

use A3020\PageFeedback\Store\BaseSender;
use A3020\PageFeedback\Store\Handler;
use Exception;

class Simple extends BaseSender implements Handler
{
    /**
     * @throws Exception
     */
    public function store()
    {
        $data = $this->getSubmittedData();

        $email = $this->request->request->get('email');
        if ($this->form->isEmailFieldEnabled() && !empty($email)) {
            $this->mailService->replyto($email);
            $data[t('Email address')] = $this->request->request->get('email');
        }

        $this->checkSpam($data);

        $this->mailService->to($this->getEmailRecipient($this->form));
        $this->mailService->addParameter('url', $this->page->getCollectionLink(true));
        $this->mailService->addParameter('data', $data);
        $this->mailService->load('page_feedback/submission/simple', 'page_feedback');
        $this->mailService->sendMail();
    }

    protected function getSubmittedData()
    {
        return [
            t('Comments') => $this->request->request->get('comments'),
        ];
    }
}
