<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Account;
use App\Entity\Repository\AccountRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AccountEntityManager.
 *
 * @method AccountRepository getEntityRepository()
 */
class AccountEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Account::class;

    const ACCOUNT_NOT_FOUND = 'Account not found';

    public function findSubresource(string $identifier, array $context): Account
    {
        $account = $this->getEntityRepository()->findOneBy(['guid' => $context['subresource_identifier'][$identifier]]);

        if (!$account instanceof Account) {
            throw new NotFoundHttpException(self::ACCOUNT_NOT_FOUND);
        }

        return $account;
    }
}
