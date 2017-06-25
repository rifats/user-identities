<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Controller\UserController;
use User\Service\UserManager;
use User\Service\AuthManager;

/**
 * This is the factory for UserController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager  = $container->get('doctrine.entitymanager.orm_default');
        $authService    = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager    = $container->get(UserManager::class);
        $authManager    = $container->get(AuthManager::class);

        // Instantiate the controller and inject dependencies
        return new UserController($entityManager, $authService, $userManager, $authManager);
    }
}