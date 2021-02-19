<?php

namespace Concrete\Package\PageFeedback;

use A3020\PageFeedback\Provider;
use A3020\PageFeedback\Installer;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\ProviderInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Package as PackageFacade;
use Concrete\Core\Support\Facade\Log;
use Exception;

final class Controller extends Package implements ProviderAggregateInterface
{
    protected $pkgHandle = 'page_feedback';
    protected $appVersionRequired = '8.3.1';
    protected $pkgVersion = '0.9.15';
    protected $pkgAutoloaderRegistries = [
        'src/PageFeedback' => '\A3020\PageFeedback',
    ];

    public function getPackageName()
    {
        return t('Page Feedback');
    }

    public function getPackageDescription()
    {
        return t('Allow visitors to leave feedback on a page.');
    }

    public function on_start()
    {
        /** @var Provider $provider */
        $provider = $this->app->make(Provider::class);
        $provider->register();
    }

    public function install()
    {
        $pkg = parent::install();

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function upgrade()
    {
        parent::upgrade();

        /** @see \Concrete\Core\Package\PackageService */
        $pkg = PackageFacade::getByHandle($this->pkgHandle);

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function uninstall()
    {
        parent::uninstall();

        try {
            $db = $this->app->make(Connection::class);
            $db->executeQuery('DROP TABLE IF EXISTS PageFeedbackForms');
        } catch (Exception $e) {
            Log::addDebug($e->getMessage());
        }
    }

    /**
     * Make sure only the entity classes are parsed
     *
     * @return ProviderInterface
     */
    public function getEntityManagerProvider()
    {
        $provider = new StandardPackageProvider($this->app, $this, [
            'src/PageFeedback/Entity' => 'A3020\PageFeedback\Entity',
        ]);

        return $provider;
    }
}
