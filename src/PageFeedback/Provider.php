<?php

namespace A3020\PageFeedback;

use A3020\PageFeedback\Listener\PageView;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Routing\RouterInterface;

class Provider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(Repository $config, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    public function register()
    {
        if ($this->config->get('page_feedback.enabled', true) === false) {
            return;
        }

        $this->router->registerMultiple([
            '/ccm/page_feedback/form' => [
                '\A3020\PageFeedback\Ajax\LoadForm::view',
            ],
            '/ccm/page_feedback/submit' => [
                '\A3020\PageFeedback\Ajax\Submit::view',
            ],
        ]);

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
        $dispatcher = $this->app['director'];

        $dispatcher->addListener('on_page_view', function ($event) {
            /** @var PageView $listener */
            $listener = $this->app->make(PageView::class);
            $listener->handle($event);
        });
    }
}
