<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Constants\Transport\JobConstants;
use App\Entity\Account;
use App\Entity\AccountStatusType;
use App\Entity\Brokerage;
use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\Factory\JobFactory;
use App\Entity\Factory\JobItemFactory;
use App\Entity\Factory\SourceFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\Job;
use App\Entity\Source;
use App\Entity\SourceType;
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
            ->setDescription($data['description'] ?? null)
            ->setBrokerage($this->findOneBy(Brokerage::class, 'name', $data['brokerage']))
            ->setAccountStatusType(($this->findOneBy(AccountStatusType::class, 'name', $data['accountStatusType'])))
            ->setApiEndpointUrl($data['apiEndpointUrl'])
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
     * @Given I have a job with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveAJobWithTheFollowingValues(Tablenode $table)
    {
        $data = $table->getRowsHash();
        $account = $this->findOneBy(Account::class, 'guid', $data['account']);
        $source = $this->findOneBy(Source::class, 'guid', $data['source']);
        $user = $this->findOneBy(User::class, 'guid', $data['user']);
        $entity = JobFactory::create()
            ->setGuid($this->getGuid($data['guid'] ?? null))
            ->setName($data['name'])
            ->setDescription($data['description'] ?? $data['name'].' description')
            ->setStatus($data['status'] ?? JobConstants::JOB_CREATED)
            ->setAccount($account)
            ->setSource($source)
            ->setUser($user)
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');
        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @Given I have a jobItem with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveAJobItemWithTheFollowingValues(Tablenode $table)
    {
        $data = $table->getRowsHash();
        $job = $this->findOneBy(Job::class, 'guid', $data['job']);
        $entity = JobItemFactory::create()
            ->setGuid($this->getGuid($data['guid']) ?? null)
            ->setData($data['data'])
            ->setStatus($data['status'] ?? JobConstants::JOB_CREATED)
            ->setJob($job)
            ->setErrorMessage($data['errorMessage'] ?? null)
            ->setErrorTrace($data['errorTrace'] ?? null)
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');
        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @Given I have a source with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveASourceWithTheFollowingValues(Tablenode $table)
    {
        $data = $table->getRowsHash();
        $sourceType = $this->findOneBy(SourceType::class, 'name', $data['sourceType']);
        $entity = SourceFactory::create()
            ->setGuid($this->getGuid($data['guid']) ?? null)
            ->setName($data['name'])
            ->setDescription($data['description'] ?? $data['name'].' description')
            ->sourceType($sourceType)
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture')
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
