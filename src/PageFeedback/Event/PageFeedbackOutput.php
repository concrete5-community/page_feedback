<?php

namespace A3020\PageFeedback\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

class PageFeedbackOutput extends GenericEvent
{
    public function getCode()
    {
        return $this->getArgument('code');
    }

    public function setCode($code)
    {
        $this->setArgument('code', $code);
    }
}
