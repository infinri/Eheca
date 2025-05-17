<?php

namespace Modules\Core_Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Modules\Core_Auth\Entity\Customer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private ?LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, ?LoggerInterface $logger = null)
    {
        parent::__construct($registry, Customer::class);
        $this->logger = $logger;
        
        if ($this->logger) {
            $this->logger->debug('CustomerRepository initialized');
        }
    }

    public function save(Customer $customer, bool $flush = true): void
    {
        $this->getEntityManager()->persist($customer);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $customer, bool $flush = true): void
    {
        $this->getEntityManager()->remove($customer);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Customer) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->save($user, true);
    }

    public function createCustomer(
        string $email, 
        string $plainPassword,
        \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher,
        ?string $firstName = null, 
        ?string $lastName = null
    ): Customer {
        $this->getEntityManager()->beginTransaction();
        
        try {
            $customer = new Customer();
            $customer->setEmail($email);
            
            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($customer, $plainPassword);
            $customer->setPassword($hashedPassword);
            
            if ($firstName) {
                $customer->setFirstName($firstName);
            }
            
            if ($lastName) {
                $customer->setLastName($lastName);
            }
            
            // Set default role if not set
            if (empty($customer->getRoles())) {
                $customer->setRoles(['ROLE_USER']);
            }
            
            // Set timestamps
            $now = new \DateTimeImmutable();
            $customer->setCreatedAt($now);
            $customer->setUpdatedAt($now);
            
            // Save the customer
            $this->getEntityManager()->persist($customer);
            $this->getEntityManager()->flush();
            
            // Commit the transaction
            $this->getEntityManager()->commit();
            
            return $customer;
        } catch (\Exception $e) {
            // Rollback the transaction on error
            $this->getEntityManager()->rollback();
            
            // Log the error
            if (isset($this->logger)) {
                $this->logger->error('Failed to create customer', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            throw $e;
        }
    }
}
