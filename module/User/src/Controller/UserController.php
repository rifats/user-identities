<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Form\UserForm;
use Zend\Authentication\Result;
use User\Form\PasswordChangeForm;
use User\Form\PasswordResetForm;
use Zend\Form\Element;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class UserController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * User manager.
     * @var \User\Service\UserManager
     */
    private $userManager;

    /**
     * Auth service.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Auth manager.
     * @var \User\Service\AuthManager
     */
    private $authManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $authService, $userManager, $authManager)
    {
        $this->entityManager    = $entityManager;
        $this->authService      = $authService;
        $this->userManager      = $userManager;
        $this->authManager      = $authManager;
    }

    /**
     * @return array|ViewModel
     * This is the default "index" action of the controller. It displays the
     * list of users.
     */
    public function indexAction()
    {
        $users = $this->entityManager->getRepository(User::class)
            ->findBy([], ['id'=>'ASC']);

        return new ViewModel([
            'users' => $users
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * Displays a page allowing to add a new user.
     */
    public function addAction()
    {
        // Create user form
        #$csrf = new Element\Csrf('csrf');
        $form = new UserForm('create', $this->entityManager);
        #$form->add($csrf);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add user
                $user = $this->userManager->addUser($data);

                // Remove current user from session
                $this->authService->clearIdentity();

                // Just added user authorization
                $result = $this->authManager
                    ->login($data['username'], $data['email'], $data['password'], $data['remember_me']);

                // Check result
                if ($result->getCode() == Result::SUCCESS) {
                    if ($this->authService->hasIdentity()) {
                    // Identity exists; get it
                    $user = $this->authService->getIdentity();
                    }
                }

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$user->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * @return ViewModel
     *
     * Displays a page allowing to view user's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'user' => $user
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * Displays a page allowing to edit user.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create user form
        #$csrf = new Element\Csrf('csrf');
        $form = new UserForm('update', $this->entityManager, $user);
        #$form->add($csrf);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update the user.
                $this->userManager->updateUser($user, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$user->getId()]);
            }
        } else {
            $form->setData(array(
                'username'  =>  $user->getUsername(),
                'email'     =>  $user->getEmail(),
                'status'    =>  $user->getStatus(),
            ));
        }

        return new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * Displays a page allowing to change user's password.
     */
    public function changePasswordAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create "change password" form
        $form = new PasswordChangeForm('change');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Sorry, the old password is incorrect. Could not set the new password.');
                } else {
                    $this->flashMessenger()->addSuccessMessage(
                        'Changed the password successfully.');
                }

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$user->getId()]);
            }
        }

        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * Displays the "Reset Password" page.
     */
    public function resetPasswordAction()
    {
        // Create form
        $form = new PasswordResetForm();

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                    ->findOneByEmail($data['email']);
                if ($user!=null) {
                    // Generate a new password for user and send an E-mail 
                    // notification about that.
                    $this->userManager->generatePasswordResetToken($user);

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action'=>'message', 'id'=>'sent']);
                } else {
                    return $this->redirect()->toRoute('users',
                        ['action'=>'message', 'id'=>'invalid-email']);
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * @return ViewModel
     * @throws \Exception
     *
     * Displays an informational message page.
     * For example "Your password has been resetted" and so on.
     */
    public function messageAction()
    {
        // Get message ID from route.
        $id = (string)$this->params()->fromRoute('id');

        // Validate input argument.
        if($id!='invalid-email' && $id!='sent' && $id!='set' && $id!='failed') {
            throw new \Exception('Invalid message ID specified');
        }

        return new ViewModel([
            'id' => $id
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Exception
     *
     * Displays the "Reset Password" page.
     */
    public function setPasswordAction()
    {
        $token = $this->params()->fromQuery('token', null);

        // Validate token length
        if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
            throw new \Exception('Invalid token type or length');
        }

        if($token===null ||
            !$this->userManager->validatePasswordResetToken($token)) {
            return $this->redirect()->toRoute('users',
                ['action'=>'message', 'id'=>'failed']);
        }

        // Create form
        $form = new PasswordChangeForm('reset');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                $data = $form->getData();

                // Set new password for the user.
                if ($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action'=>'message', 'id'=>'set']);
                } else {
                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action'=>'message', 'id'=>'failed']);
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     *
     * Deletes User with all related identities.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Verification of the existence of the user
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if( !$user ) {
            throw new \Exception('Such user does not exist');
        }

        $username = $user->getUsername();

        // Delete user.
        $result = $this->userManager->deleteUser($user);

        // TODO Проверить Flash messages!
        if( $result ) {
            // Success Flash message
            $this->flashMessenger()->addSuccessMessage(
                'User '. $username .' deleted from database');
        } else {
            // Fails Flash message
            $this->flashMessenger()->addErrorMessage(
                'An error has occurred. The user is not deleted.');
        }

        // Redirect to "index" page
        return $this->redirect()->toRoute('users');
    }
}
