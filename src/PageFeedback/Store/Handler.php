<?php

namespace A3020\PageFeedback\Store;

use A3020\PageFeedback\Entity\Form;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;

interface Handler
{
    /**
     * Handle a submission
     *
     * Store a submission, or send it via email.
     * Each form type has its own handler implementation.
     */
    public function store();

    /**
     * @param Request $request
     *
     * @return self
     */
    public function setRequest(Request $request);

    /**
     * @param Page $page
     *
     * @return self
     */
    public function setPage(Page $page);

    /**
     * @param Form $form
     *
     * @return self
     */
    public function setForm(Form $form);
}