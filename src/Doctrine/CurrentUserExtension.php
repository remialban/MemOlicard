<?php

namespace App\Doctrine;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\CardsList;
use Doctrine\ORM\QueryBuilder;
use App\Repository\CardsListRepository;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{ 
    private Security $security;
    private User $user;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->user = $this->security->getUser();
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];

        if ($resourceClass == CardsList::class)
        {
            $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $this->user->getId());
        }
        if ($resourceClass == User::class)
        {
            $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $this->user->getId());
        }
        if ($resourceClass == Card::class)
        {
            $queryBuilder->leftJoin(sprintf('%s.cardsList', $rootAlias), 'cl');

            $queryBuilder->andWhere(sprintf('cl.user = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $this->user->getId());
        }
    }
}