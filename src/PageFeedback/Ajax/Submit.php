<?php

namespace A3020\PageFeedback\Ajax;

use A3020\PageFeedback\Submitter;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\View\View;
use Exception;

class Submit extends \Concrete\Core\Controller\Controller implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    public function view()
    {
        try {
            /** @var Submitter $submitter */
            $submitter = $this->app->make(Submitter::class);
            $submitter->submit();

            $response = $this->thanks();
        } catch (Exception $e) {
            $response = $this->app->make(ResponseFactory::class)
                ->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function thanks()
    {
        $factory = $this->app->make(ResponseFactory::class);

        $view = new View('page_feedback/thanks');
        $view->setPackageHandle('page_feedback');

        return $factory->create($view->render());
    }
}
