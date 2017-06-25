<?php

namespace Identity\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Identity\Entity\Identity;
use Identity\Form\IdentityForm;

/**
 * Class IdentityController
 * @package Identity\Controller
 */
class IdentityController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Identity manager.
     * @var \Identity\Service\IdentityManager
     */
    private $identityManager;

    /**
     * Auth service.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;

    // Add this constructor:
    public function __construct($entityManager, $authService, $identityManager)
    {
        $this->entityManager    = $entityManager;
        $this->authService      = $authService;
        $this->identityManager  = $identityManager;
    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $identities = $this->entityManager->getRepository(Identity::class)
            ->findAll;

        return new ViewModel([
            'identities' => $identities,
        ]);
    }

    public function addAction()
    {
        // Current user in session
        $authorizedUser = $this->authService->getIdentity();

        // Create identity form
        $form = new IdentityForm('create', $this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add identity.
                $identity = $this->identityManager->addIdentity($data, $authorizedUser);

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$identity->getUser()->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        // Create form
        $form = new IdentityForm();
        
        // Проверяем, отправил ли пользователь форму
        if($this->getRequest()->isPost()) 
        {
            // Заполняем форму POST-данными
            $data = $this->params()->fromPost();            
            $form->setData($data);
            
            // Валидируем форму
            if($form->isValid()) {
                
                // Получаем фильтрованные и валидированные данные
                $data = $form->getData();
                
                // ... Какие-то действия с валидированными данными ...
		
                // Перенаправление на страницу "Спасибо"
                return $this->redirect()->toRoute('identity', ['action'=>'index']);
            }            
        } 
        
        // Передаем переменную формы представлению
        return new ViewModel([
           'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     *
     * Deletes Identity.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Verification of the existence of the identity
        $identity = $this->entityManager->getRepository(Identity::class)
            ->find($id);

        if( !$identity ) {
            throw new \Exception('Such identity does not exist');
        }

        $userId = $identity->getUser()->getId();

        // Delete identity.
        $result = $this->identityManager->deleteIdentity($identity);

        // TODO Проверить Flash messages!
        if( $result ) {
            // Success Flash message
            $this->flashMessenger()->addSuccessMessage(
                'Identity has been deleted from database');
        } else {
            // Fails Flash message
            $this->flashMessenger()->addErrorMessage(
                'An error has occurred. The identity is not deleted.');
        }

        // Redirect to user page
        return $this->redirect()->toRoute('users',
            ['action'=>'view', 'id'=>$userId]);
    }
}