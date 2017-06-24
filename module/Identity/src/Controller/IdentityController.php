<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Identity\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Identity\Entity\Identity;

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

    // Add this constructor:
    public function __construct($entityManager, $identityManager)
    {
        $this->entityManager = $entityManager;
        $this->identityManager = $identityManager;
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

    public function deleteAction()
    {
    }
}