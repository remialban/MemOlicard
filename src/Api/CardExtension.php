<?php

namespace App\Api;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use App\Entity\Card;
use App\Entity\User;
use Doctrine\ORM\Query\Expr;

class CardExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private Security $security)
    {
        
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null)
    {
        if ($resourceClass !== Card::class || $operationName != "get")
        {
            return;
        }

        $this->addWhere($queryBuilder);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        if ($resourceClass != Card::class || $operationName != "get")
        {
            return;
        }

        $this->addWhere($queryBuilder);        
    }

    private function addWhere(QueryBuilder $queryBuilder)
    {
        $currentUser = $this->security->getUser();

        if ($currentUser instanceof User)
        {
            $userId = $currentUser->getId();
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->innerJoin(
                "$rootAlias.cardsList",
                "g",
                Expr\Join::WITH,
                "g.user = $userId"
            );
        }
    }
}
