<?php
namespace Identity\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Identity\Controller\IdentityController;
use Identity\Service\IdentityManager;

/**
 * Это фабрика для IndexController. Ее целью является инстанцирование
 * контроллера.
 */
class IdentityControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $identityManager = $container->get(IdentityManager::class);

        // Инстанцируем контроллер и внедряем зависимости.
        return new IdentityController($entityManager, $identityManager);
    }
}