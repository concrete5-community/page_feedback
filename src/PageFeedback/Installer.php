<?php

namespace A3020\PageFeedback;

use Concrete\Core\Database\DatabaseStructureManager;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Doctrine\ORM\EntityManager;

class Installer
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Concrete\Core\Package\Package $pkg
     */
    public function install($pkg)
    {
        $this->refreshEntities();
        $this->dashboardPages($pkg);
    }

    private function dashboardPages($pkg)
    {
        $pages = [
            '/dashboard/system/page_feedback' => t('Page Feedback'),
            '/dashboard/system/page_feedback/forms' => t('Feedback Forms'),
            '/dashboard/system/page_feedback/settings' => t('Settings'),
        ];

        foreach ($pages as $path => $name) {
            /** @var Page $page */
            $page = Page::getByPath($path);
            if ($page && !$page->isError()) {
                continue;
            }

            $singlePage = Single::add($path, $pkg);
            $singlePage->update([
                'cName' => $name,
            ]);
        }
    }

    private function refreshEntities()
    {
        $manager = new DatabaseStructureManager($this->entityManager);

        // This method isn't available in 8.0.0
        if (method_exists($manager, 'refreshEntities')) {
            $manager->refreshEntities();
        } else {
            $manager->generateProxyClasses();
        }
    }
}
