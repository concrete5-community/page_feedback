<?php

namespace Concrete\Package\PageFeedback\Controller\SinglePage\Dashboard\System;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class PageFeedback extends DashboardPageController
{
    public function view()
    {
        return Redirect::to('/dashboard/system/page_feedback/forms');
    }
}
