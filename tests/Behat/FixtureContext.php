<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Constants\Transport\JobConstants;
use App\DataFixtures\AccountFixture;
use App\DataFixtures\AccountStatusTypeFixture;
use App\DataFixtures\BrokerageFixture;
use App\DataFixtures\SourceFixture;
use App\DataFixtures\SourceTypeFixture;
use App\DataFixtures\TickerFixture;
use App\DataFixtures\TickerTypesFixture;
use App\DataFixtures\UserFixture;
use App\DataFixtures\UserTypeFixture;
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
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\ServiceAccountService;
use App\Tests\Behat\Traits\RestContextTrait;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class FixtureContext extends BaseFixtureContext
{
    use RestContextTrait;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var ORMExecutor
     */
    private $ormExecutor;

    /**
     * @var ORMPurger
     */
    private $ormPurger;

    /**
     * @var ValidationHelper
     */
    private $validator;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageProvider;

    /**
     * @var ServiceAccountService
     */
    private $serviceAccountService;

    /**
     * FixtureContext constructor.
     *
     * @param BrokerageServiceProvider $brokerageProvider
     * @param EntityManagerInterface   $entityManager
     * @param ValidationHelper         $validator
     * @param ServiceAccountService    $serviceAccountService
     */
    public function __construct(
        BrokerageServiceProvider $brokerageProvider,
        EntityManagerInterface $entityManager,
        ValidationHelper $validator,
        ServiceAccountService $serviceAccountService
    ) {
        $this->brokerageProvider = $brokerageProvider;
        $this->manager = $entityManager;
        $this->validator = $validator;
        $this->serviceAccountService = $serviceAccountService;
        parent::__construct($entityManager);
    }

    /**
     * @Given I use the :brokerage paper account
     *
     * @param string $brokerage
     */
    public function iUseThePaperAccount(string $brokerage)
    {
        $brokerage = $this->findOneBy(Brokerage::class, ['context' => strtolower($brokerage)]);
        $account = $this->findOneBy(Account::class, ['brokerage' => $brokerage, 'paper' => true]);

        $this->setReference($account->getGuid()->toString(), $account);
    }

    /**
     * @Given I have an :brokerage account with the following values:
     *
     * @param TableNode $table
     */
    public function iHaveAnAccountWithTheFollowingValues(string $brokerage, TableNode $table)
    {
        $data = $table->getRowsHash();

        $brokerage = $this->findOneBy(Brokerage::class, ['context' => strtolower($brokerage)]);
        $accountStatusType = $this->findOneBy(AccountStatusType::class, ['name' => $data['accountStatusType']]);

        $entity = AccountFactory::create()
            ->setGuid($this->getGuid($data['guid'] ?? null))
            ->setName($data['name'])
            ->setDescription($data['description'] ?? null)
            ->setBrokerage($brokerage)
            ->setAccountStatusType($accountStatusType)
            ->setApiEndpointUrl($data['apiEndpointUrl'])
            ->setApiKey($data['apiKey'])
            ->setDefault((bool) $data['default'] ?? false)
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
        $account = $this->findOneBy(Account::class, ['guid' => $data['account']]);
        $source = $this->findOneBy(Source::class, ['guid' => $data['source']]);
        $user = $this->findOneBy(User::class, ['guid' => $data['user']]);
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
        $job = $this->findOneBy(Job::class, ['guid' => $data['job']]);
        $entity = JobItemFactory::create()
            ->setGuid($this->getGuid($data['guid']) ?? null)
            ->setData($data['data'])
            ->setStatus($data['status'] ?? JobConstants::JOB_CREATED)
            ->setJob($job)
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
        $sourceType = $this->findOneBy(SourceType::class, ['name' => $data['sourceType']]);
        $entity = SourceFactory::create()
            ->setGuid($this->getGuid($data['guid']) ?? null)
            ->setName($data['name'])
            ->setDescription($data['description'] ?? $data['name'].' description')
            ->setSourceType($sourceType)
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
            ->setUserType($this->findOneBy(UserType::class, ['name' => $data['userType']]))
            ->setCreatedBy($data['createdBy'] ?? 'fixture')
            ->setModifiedBy($data['modifiedBy'] ?? 'fixture');

        $this->validator->validate($entity);
        $this->persist($entity);
        $this->setReference($entity->getGuid()->toString(), $entity);
    }

    /**
     * @BeforeScenario @clearAllFixtures
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function clearAllFixtures()
    {
        $conn = $this->manager->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0');

        $purger = $this->getOrmPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @BeforeScenario @loadAllFixtures
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadAllFixtures()
    {
        $this->clearAllFixtures();
        $this->executeFixtures([
            new AccountStatusTypeFixture(),
            new SourceTypeFixture(),
            new UserTypeFixture(),
            new UserFixture(),
            new BrokerageFixture(),
            new TickerTypesFixture($this->brokerageProvider, $this->entityManager),
            new TickerFixture(),
            new AccountFixture(),
            new SourceFixture(),
        ]);
    }

    /**
     * @BeforeScenario @loadBrokerageFixtures
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadBrokerageFixtures()
    {
        $this->clearAllFixtures();
        $this->executeFixtures([
            new AccountStatusTypeFixture(),
            new UserTypeFixture(),
            new UserFixture(),
            new BrokerageFixture(),
        ]);
    }

    /**
     * @BeforeScenario @loadAccountFixtures
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadAccountFixtures()
    {
        $this->clearAllFixtures();
        $this->executeFixtures([
            new AccountStatusTypeFixture(),
            new UserTypeFixture(),
            new UserFixture(),
            new BrokerageFixture(),
            new AccountFixture(),
        ]);
    }

    private function executeFixtures(array $fixtures)
    {
        $executor = $this->getOrmExecutor();
        $executor->execute($fixtures);
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

    /**
     * @return ORMExecutor
     */
    private function getOrmExecutor(): ORMExecutor
    {
        if (!$this->ormExecutor instanceof OrmExecutor) {
            return new OrmExecutor($this->manager, $this->getOrmPurger());
        }

        return $this->ormExecutor;
    }

    /**
     * @return ORMPurger
     */
    private function getOrmPurger(): ORMPurger
    {
        if (!$this->ormPurger  instanceof ORMPurger) {
            return new OrmPurger($this->manager);
        }

        return $this->ormPurger;
    }
}
