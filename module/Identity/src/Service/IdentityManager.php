<?php
namespace Identity\Service;

use Identity\Entity\Identity;

/**
 * Class IdentityManager
 * @package Identity\Service
 * This service is responsible for adding/editing identities
 */
class IdentityManager
{
    /**
     * Doctrine entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new identity.
     */
    public function addIdentity($data, $authorizedUser=null)
    {
        // Do not allow identity without userId
        if( !$authorizedUser ) {
            throw new \Exception("Identity must belong to a user");
        }

        $user = $this->entityManager->getRepository(\User\Entity\User::class)
            ->find($authorizedUser->getId());

        // Do not allow identity with same Serial number.
        /*
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }
        */

        // Create new Identity entity.
        $identity = new Identity();
        $identity->setIdenttype($data['identType']);
        $identity->setName($data['name']);
        $identity->setSurname($data['surname']);
        $identity->setUser($user);
        // Другие поля добавить

        $currentDate = date('Y-m-d H:i:s');
        $identity->setDateCreated($currentDate);

        // Add the entity to the entity manager.
        $this->entityManager->persist($identity);

        // Apply changes to database.
        $this->entityManager->flush();

        return $identity;
    }



}