<?php

namespace A3020\PageFeedback;

use A3020\PageFeedback\Entity\Form;
use Concrete\Core\Page\Page;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

class FormMatcher
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Find a matching form by a page object
     *
     * @param Page $page
     *
     * @return Form|null
     */
    public function findByPage(Page $page)
    {
        $url = $page->getCollectionLink();

        foreach ($this->getEnabledForms() as $form) {
            if ($this->matches($form, $url)) {
                return $form;
            }
        }

        return null;
    }

    /**
     * Return true if the form is enabled for this page
     *
     * @param Form $form
     * @param string $url
     *
     * @return bool
     */
    private function matches(Form $form, $url)
    {
        try {
            // If no patterns are configured, always show the form
            if (empty($form->getUrlsInclude()) && empty($form->getUrlsExclude())) {
                return true;
            }

            if (!empty($form->getUrlsExclude())) {
                // Check if current URL is excluded (case insensitive)
                if (fnmatch($form->getUrlsExclude(), $url, FNM_CASEFOLD)) {
                    return false;
                }
            }

            if (!empty($form->getUrlsInclude())) {
                // Check if current URL is included (case insensitive)
                return fnmatch($form->getUrlsInclude(), $url, FNM_CASEFOLD);
            }

            return true;
        } catch (Throwable $e) {
            $this->logger->debug(
                'Page Feedback, form (' . $form->getName() . ') gives an error: '
                . $e->getMessage()
            );

            return false;
        }
    }

    /**
     * Return a list of enabled feedback forms
     *
     * @return Form[]
     */
    private function getEnabledForms()
    {
        return $this->entityManager->getRepository(Form::class)
            ->findBy([
                'isEnabled' => true,
            ]);
    }
}
