<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\AccountStatusType;
use App\Entity\Brokerage;
use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Entity\UserType;
use App\Helper\ValidationHelper;
use App\Service\ServiceAccountService;
use App\Tests\Behat\Traits\RestContextTrait;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class FixtureContext extends BaseFixtureContext
{
    use RestContextTrait;

    private $validator;

    private $serviceAccountService;

    public function __construct(
        EntityManagerInterface $entitymanager,
        ValidationHelper $validator,
        ServiceAccountService $serviceAccountService
    ) {
        $this->validator = $validator;
        $this->serviceAccountService = $serviceAccountService;
        parent::__construct($entitymanager);
    }

    /**
     * @Given I have an account with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveAnAccountWithTheFollowingValues(TableNode $table)
    {
        $data = $table->getRowsHash();
        $entity = AccountFactory::create()
            ->setGuid($this->getGuid($data['guid'] ?? null))
            ->setName($data['name'])
            ->setBrokerage($this->findOneBy(Brokerage::class, 'name', $data['brokerage']))
            ->setAccountStatusType(($this->findOneBy(AccountStatusType::class, 'name', $data['accountStatusType'])))
            ->setApiEndpointUrl($data['apiEndpoint'])
            ->setApiKey($data['apiKey'])
            ->setApiSecret($data['apiSecret'])
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');

        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @Given I have a brokerage with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveABrokerageWithTheFollowingValues(Tablenode $table)
    {
        $data = $table->getRowsHash();
        $entity = BrokerageFactory::create()
            ->setGuid($this->getGuid($data['guid'] ?? null))
            ->setName($data['name'])
            ->setUrl($data['url'] ?? 'http://localhost')
            ->setDescription($data['description'] ?? 'test brokerage')
            ->setApiDocumentUrl($data['apiDocumentationUrl'] ?? 'http://document.url.com')
            ->setContext($data['context'] ?? 'context')
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');

        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @Given I have a user with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveAUserWithTheFollowingValues(TableNode $table)
    {
        $data = $table->getRowsHash();
        $entity = UserFactory::create()
            ->setGuid($this->getGuid($data['guid']) ?? null)
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setUsername($data['username'])
            ->setUserType($this->findOneBy(UserType::class, 'name', $data['userType']))
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');

        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @param string|null $guid
     *
     * @return \Ramsey\Uuid\UuidInterface
     */
    private function getGuid(?string $guid)
    {
        if (null === $guid) {
            return Uuid::uuid1();
        }

        return Uuid::fromString($guid);
    }
}
