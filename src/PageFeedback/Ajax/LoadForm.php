<?php

namespace A3020\PageFeedback\Ajax;

use A3020\PageFeedback\FormMatcher;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Error\UserMessageException;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfoRepository;
use Concrete\Core\View\View;

class LoadForm extends \Concrete\Core\Controller\Controller implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws UserMessageException
     */
    public function view()
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(ResponseFactory::class);
        $formEntity = $this->getForm();

        if (!$formEntity) {
            throw new UserMessageException('No form found for this page.');
        }

        $view = new View('page_feedback/form/' . $formEntity->getType());
        $view->setPackageHandle('page_feedback');
        $view->addScopeItems([
            'formEntity' => $formEntity,
            'token' => $this->app->make('token'),
            'form' => $this->app->make('helper/form'),
            'defaultEmailAddress' => $this->getDefaultEmailAddress(),
            'triggerEcRecaptcha' => $this->isRecaptchaInstalled(),
        ]);

        return $responseFactory->create($view->render());
    }

    /**
     * @return \A3020\PageFeedback\Entity\Form|null
     */
    private function getForm()
    {
        /** @var FormMatcher $formMatcher
         */
        $formMatcher = $this->app->make(FormMatcher::class);

        return $formMatcher->findByPage(
            Page::getByID(
                (int) $this->request->get('cid')
            )
        );
    }

    /**
     * @return string
     */
    private function getDefaultEmailAddress()
    {
        $user = new User();

        if (!$user->isRegistered()) {
            return '';
        }

        /** @var UserInfoRepository $userInfoRepository */
        $userInfoRepository = $this->app->make(UserInfoRepository::class);

        $ui = $userInfoRepository->getByID($user->getUserID());

        return $ui ? $ui->getUserEmail() : '';
    }

    /**
     * Return true if reCAPTCHA add-on is installed
     *
     * If it's installed, it needs to be triggered manually
     * because the form is loaded via AJAX.
     *
     * @see https://www.concrete5.org/marketplace/addons/recaptcha
     *
     * @return bool
     */
    private function isRecaptchaInstalled()
    {
        $package = $this->app->make(PackageService::class)
            ->getByHandle('ec_recaptcha');

        return is_object($package) && $package->isPackageInstalled();
    }
}
