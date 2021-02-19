<?php

namespace Concrete\Package\PageFeedback\Controller\SinglePage\Dashboard\System\PageFeedback;

use A3020\PageFeedback\Entity\Form;
use A3020\PageFeedback\FormRepository;
use A3020\PageFeedback\Service;
use Concrete\Core\Http\Request;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Forms extends DashboardPageController
{
    public function view()
    {
        $formRepository = $this->getFormRepository();

        $this->set('forms', $formRepository->getAll());
        $this->set('administratorEmail', $this->getAdministratorEmail());
    }
    
    public function add()
    {
        $this->set('pageTitle', t('Add form'));

        $form = new Form();
        $form->setName(t('Default'));
        $form->setAcceptTermsText(t('I accept the terms of use'));

        return $this->addEdit($form);
    }

    public function edit($id = null)
    {
        $entity = $this->getFormRepository()->find($id);

        if (!$entity) {
            $this->flash('error', 'Form not found');

            return Redirect::to('/dashboard/system/page_feedback/forms');
        }

        $this->set('pageTitle', t('Edit %s', $entity->getName()));

        return $this->addEdit($entity);
    }

    private function addEdit(Form $entity)
    {
        $this->set('entity', $entity);
        $this->set('typeOptions', $this->getTypeOptions());
        $this->set('administratorEmail', $this->getAdministratorEmail());
        $this->set('editor', $this->app->make('editor'));
        $this->set('isEcRecaptchaInstalled', $this->isEcRecaptchaInstalled());

        return $this->render('/dashboard/system/page_feedback/forms/add_edit');
    }

    public function delete($id = null)
    {
        $formRepository = $this->getFormRepository();

        $entity = $formRepository->find($id);

        if (!$entity) {
            $this->flash('error', 'Form not found');

            return Redirect::to('/dashboard/system/page_feedback/forms');
        }

        $formRepository->delete($entity);

        $this->flash('success', t('Feedback form has been deleted successfully.'));

        return Redirect::to('/dashboard/system/page_feedback/forms');
    }

    public function save()
    {
        if (!$this->token->validate('a3020.page_feedback.forms')) {
            $this->flash('error', $this->token->getErrorMessage());

            return;
        }

        /** @var Request $request */
        $request = $this->app->make(Request::class);

        $entity = $this->getFormRepository()->find($request->request->get('id'));
        if (!$entity) {
            $entity = new Form();
        }

        $entity->setIsEnabled($request->request->has('isEnabled'));
        $entity->setName($request->request->get('name'));
        $entity->setEnableEmailField($request->request->has('enableEmailField'));
        $entity->setEmailFieldRequired($request->request->has('emailFieldRequired'));
        $entity->setType('simple');
        $entity->setEmailRecipient($request->request->get('emailRecipient'));
        $entity->setEnableCaptcha($request->request->has('enableCaptcha'));
        $entity->setEnableAutoClose($request->request->has('enableAutoClose'));
        $entity->setEnableAcceptTerms($request->request->has('enableAcceptTerms'));
        $entity->setAcceptTermsText($request->request->get('acceptTermsText'));
        $entity->setButtonCaption($request->request->get('buttonCaption'));
        $entity->setIntroText($request->request->get('introText'));
        $entity->setUrlsInclude($request->request->get('urlsInclude'));
        $entity->setUrlsExclude($request->request->get('urlsExclude'));

        $this->getFormRepository()->store($entity);

        $this->flash('success', t('Feedback form has been saved successfully.'));

        if ($request->request->has('saveAndClose')) {
            return Redirect::to('/dashboard/system/page_feedback/forms');
        }

        return Redirect::to('/dashboard/system/page_feedback/forms/edit/' . $entity->getId());
    }

    /**
     * Return a list of possible feedback form types
     *
     * @return array
     */
    private function getTypeOptions()
    {
        return [
            'simple' => t('Simple'),
        ];
    }

    /**
     * @return FormRepository
     */
    private function getFormRepository()
    {
        return $this->app->make(FormRepository::class);
    }

    private function getAdministratorEmail()
    {
        /** @var Service $service */
        $service = $this->app->make(Service::class);

        return $service->getAdministratorEmail();
    }

    /**
     * @return bool
     */
    private function isEcRecaptchaInstalled()
    {
        $package = $this->app->make(PackageService::class)
            ->getByHandle('ec_recaptcha');

        return is_object($package) && $package->isPackageInstalled();
    }
}
