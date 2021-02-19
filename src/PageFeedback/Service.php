<?php

namespace A3020\PageFeedback;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\User\UserInfoRepository;

class Service implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @return string
     */
    public function getAdministratorEmail()
    {
        /** @var \Concrete\Core\User\UserInfo $admin */
        $admin = $this->app->make(UserInfoRepository::class)->getByID(1);

        return $admin->getUserEmail();
    }
}
