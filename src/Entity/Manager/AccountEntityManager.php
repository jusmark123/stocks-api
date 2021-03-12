<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Account;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AccountEntityManager.
 */
class AccountEntityManager extends EntityManager
{
    const ENTITY_CLASS = Account::class;

    const ACCOUNT_NOT_FOUND = 'AlpacaAccount not found';

    /**
     * @param string $identifier
     * @param array  $context
     *
     * @return Account
     */
    public function findSubresource(string $identifier, array $context): Account
    {
        $account = $this->getEntityRepository()->findOneBy(['guid' => $context['subresource_identifier'][$identifier]]);

        if (!$account instanceof Account) {
            throw new NotFoundHttpException(self::ACCOUNT_NOT_FOUND);
        }

        return $account;
    }
}
