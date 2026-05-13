<?php

namespace App\Repository;

use App\Entity\Equipe;
use App\Entity\JoinRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JoinRequest>
 */
class JoinRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JoinRequest::class);
    }

    /**
     * Check if an active invitation or join request already exists for a user/team pair.
     */
    public function findActiveForUserAndTeam(User $user, Equipe $equipe): ?JoinRequest
    {
        return $this->createQueryBuilder('jr')
            ->where('jr.user = :user')
            ->andWhere('jr.equipe = :equipe')
            ->andWhere('jr.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('equipe', $equipe)
            ->setParameter('statuses', ['invited', 'pending'])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get all pending invitations (status='invited') sent to a given user.
     */
    public function findPendingInvitationsForUser(User $user): array
    {
        return $this->createQueryBuilder('jr')
            ->where('jr.user = :user')
            ->andWhere('jr.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'invited')
            ->orderBy('jr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all sent invitations by an owner for a team.
     */
    public function findSentInvitationsForTeam(Equipe $equipe): array
    {
        return $this->createQueryBuilder('jr')
            ->where('jr.equipe = :equipe')
            ->andWhere('jr.status = :status')
            ->setParameter('equipe', $equipe)
            ->setParameter('status', 'invited')
            ->orderBy('jr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all pending join requests (users asking to join) for a team.
     */
    public function findPendingRequestsForTeam(Equipe $equipe): array
    {
        return $this->createQueryBuilder('jr')
            ->where('jr.equipe = :equipe')
            ->andWhere('jr.status = :status')
            ->setParameter('equipe', $equipe)
            ->setParameter('status', 'pending')
            ->orderBy('jr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
