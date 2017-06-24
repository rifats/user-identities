<?php
namespace Identity\Service\Factory;

use Interop\Container\ContainerInterface;
use Identity\Service\IdentityManager;

/**
 * Class IdentityManagerFactory
 * @package Identity\Service\Factory
 * This is the factory class for IdentityManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class IdentityManagerFactory
{
    /**
     * This method creates the UserManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new IdentityManager($entityManager);
    }
}