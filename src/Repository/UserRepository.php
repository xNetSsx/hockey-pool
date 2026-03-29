<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Service\Manager\UserManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/** @extends ServiceEntityRepository<User> */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly UserManager $userManager,
    ) {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        assert($user instanceof User);
        $user->setPassword($newHashedPassword);
        $this->userManager->save($user);
    }

    /**
     * @param list<int> $ids
     * @return array<int, User>
     */
    public function findByIds(array $ids): array
    {
        if ([] === $ids) {
            return [];
        }

        $users = $this->findBy(['id' => $ids]);
        $indexed = [];
        foreach ($users as $user) {
            /** @var int $id */
            $id = $user->getId();
            $indexed[$id] = $user;
        }

        return $indexed;
    }
}
