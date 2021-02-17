<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Manager;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Manager\EntityManager;
use App\Service\EntityStateService;
use App\Tests\Unit\Entity\DummyEntityFixture;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\EntityListenerResolver;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Phake;
use PHPUnit\Framework\TestCase;

class EntityManagerTest extends TestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $SUT;

    /**
     * @Mock
     *
     * @var ClassMetadata
     */
    private ClassMetadata $classMetadata;

    /**
     * @Mock
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * @Mock
     *
     * @var EntityRepository
     */
    private EntityRepository $entityRepository;

    /**
     * @Mock
     *
     * @var FilterCollection
     */
    private FilterCollection $filterCollection;

    /**
     * @Mock
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @Mock
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * @Mock
     *
     * @var AbstractPlatform
     */
    private AbstractPlatform $platform;

    /**
     * @Mock
     *
     * @var EntityStateService
     */
    private EntityStateService $stateService;

    /**
     * @Mock
     *
     * @var EventManager
     */
    private EventManager $eventManager;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);

        $this->SUT = new DummyEntityManagerFixture(
            $this->managerRegistry,
            $this->stateService
        );

        Phake::when($this->managerRegistry)
            ->getManagerForClass(Phake::anyParameters())
            ->thenReturn($this->manager);
        Phake::when($this->manager)
            ->getRepository(Phake::anyParameters())
            ->thenReturn($this->entityRepository);
        Phake::when($this->manager)
            ->getFilters()
            ->thenReturn($this->filterCollection);
        Phake::when($this->manager)
            ->getConnection()
            ->thenReturn($this->connection);
        Phake::when($this->manager)
            ->getClassMetadata(Phake::anyParameters())
            ->thenReturn($this->classMetadata);
        Phake::when($this->connection)
            ->getDatabasePlatform()
            ->thenReturn($this->platform);
        Phake::when($this->manager)
            ->getEventManager()
            ->thenReturn($this->eventManager);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(EntityManager::class, $this->SUT);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::find()
     */
    public function testFind()
    {
        $this->assertNull($this->SUT->find(1));
        Phake::verify($this->entityRepository)->find(1);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::findAll()
     */
    public function testFindAll()
    {
        Phake::when($this->entityRepository)
            ->findAll()
            ->thenReturn([]);
        $this->assertEmpty(
            $this->SUT->findAll()
        );

        Phake::verify($this->entityRepository)->findAll();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::findOneBy()
     */
    public function testFindOneBy()
    {
        $this->assertNull($this->SUT->findOneBy([]));
        Phake::verify($this->entityRepository)->findOneBy([]);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::findBy()
     */
    public function testFindBy()
    {
        Phake::when($this->entityRepository)
            ->findBy(Phake::anyParameters())
            ->thenReturn([]);

        $this->assertEmpty($this->SUT->findBy([]));
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::persist()
     */
    public function testPersist()
    {
        Phake::when($this->stateService)
            ->getState(Phake::anyParameters())
            ->thenReturn(UnitOfWork::STATE_NEW);
        $this->assertInstanceOf(EntityInterface::class, $this->SUT->persist(new DummyEntityFixture()));

        Phake::verify($this->manager)->persist(Phake::anyParameters());
        Phake::verify($this->manager, Phake::times(0))->flush();

    }

    /**
     * @covers \App\Entity\Manager\EntityManager::persist()
     */
    public function testPersistWithFlush()
    {
        Phake::when($this->stateService)
            ->getState(Phake::anyParameters())
            ->thenReturn(UnitOfWork::STATE_DETACHED);

        $this->assertInstanceOf(
            EntityInterface::class,
            $this->SUT->persist(new DummyEntityFixture(), true)
        );

        Phake::verify($this->manager)->persist(Phake::anyParameters());
        Phake::verify($this->manager)->flush();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::flush()
     */
    public function testFlush()
    {
        $this->assertNull($this->SUT->flush());

        Phake::verify($this->manager)->flush();
        Phake::verify($this->manager, Phake::never())->clear();

        $this->assertNull($this->SUT->flush(true));

        Phake::verify($this->manager, Phake::times(2))->flush();
        Phake::verify($this->manager)->clear();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::clear()
     */
    public function testClear()
    {
        $this->assertNull($this->SUT->clear());
        Phake::verify($this->manager)->clear();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::detach()
     */
    public function testDetach()
    {
        $entity = Phake::mock(EntityInterface::class);
        $this->assertNull($this->SUT->detach($entity));
        Phake::verify($this->manager)->clear($entity);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::refresh()
     */
    public function testRefresh()
    {
        $this->assertNotNull($this->SUT->refresh(new \stdClass()));
        Phake::verify($this->manager)->refresh(Phake::anyParameters());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::truncate()
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testTruncate()
    {
        Phake::when($this->platform)
            ->getTruncateTableSQL(Phake::anyParameters())
            ->thenReturn('');
        $this->assertNull($this->SUT->truncate('table'));
        Phake::verify($this->connection)->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        Phake::verify($this->connection)->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
        Phake::verify($this->connection)->executeStatement(Phake::anyParameters());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::removeBy()
     */
    public function testRemoveBy()
    {
        $entity = Phake::mock(EntityInterface::class);
        Phake::when($this->entityRepository)->findBy(Phake::anyParameters())->thenReturn([$entity]);

        $this->assertNull($this->SUT->removeBy([]));

        Phake::verify($this->entityRepository)->findBy([], null, null, null);
        Phake::verify($this->manager)->remove($entity);
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::remove()
     */
    public function testRemove()
    {
        $this->assertInstanceOf(EntityInterface::class, $this->SUT->remove(new DummyEntityFixture()));

        Phake::verify($this->manager)->remove(Phake::anyParameters());
        Phake::verify($this->manager, Phake::times(0))->flush();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::remove()
     */
    public function testRemoveWithFlush()
    {
        $this->assertInstanceOf(
            EntityInterface::class,
            $this->SUT->remove(new DummyEntityFixture(), true)
        );

        Phake::verify($this->manager)->remove(Phake::anyParameters());
        Phake::verify($this->manager)->flush();
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getConnection()
     */
    public function testGetConnection()
    {
        $this->assertInstanceOf(Connection::class, $this->SUT->getConnection());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getEntityRepository
     */
    public function testGetEntityRepository()
    {
        $this->assertInstanceOf(EntityRepository::class, $this->SUT->getEntityRepository());
    }

    public function testGetEntityListener()
    {
        $configuration = Phake::mock(Configuration::class);
        $resolver = Phake::mock(EntityListenerResolver::class);
        Phake::when($this->manager)->getConfiguration()->thenReturn($configuration);
        Phake::when($configuration)->getEntityListenerResolver()->thenReturn($resolver);
        Phake::when($resolver)->resolve()->thenReturn([]);

        $this->assertEmpty($this->SUT->getEntityListener());

        Phake::verify($this->manager)->getConfiguration();
        Phake::verify($configuration)->getEntityListenerResolver();
        Phake::verify($resolver)->resolve(Phake::anyParameters());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getEntityManager
     */
    public function testGetEntityManager()
    {
        $this->assertInstanceOf(EntityManagerInterface::class, $this->SUT->getEntityManager());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getStateService()
     */
    public function testGetEntityStateService()
    {
        $this->assertInstanceOf(EntityStateService::class, $this->SUT->getStateService());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getMetadataFor()
     */
    public function testGetMetadataFor()
    {
        $this->assertInstanceOf(ClassMetadata::class, $this->SUT->getMetadataFor());
    }

    /**
     * @covers \App\Entity\Manager\EntityManager::getListeners()
     */
    public function testGetListeners()
    {
        Phake::when($this->eventManager)
            ->getListeners()
            ->thenReturn([]);

        $this->assertEmpty($this->SUT->getListeners());
    }
}
