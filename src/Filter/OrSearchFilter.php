<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Core\Api\IdentifiersExtractorInterface;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryBuilderHelper;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Annotation\CustomExportCustomField;
use App\Service\CustomExportService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class OrSearchFilter extends SearchFilter
{
    /**
     * @var string
     */
    protected $propertySearchName = 'search';

    /**
     * @var ArrayCollection
     */
    protected $statements;

    /**
     * @var CustomExportService
     */
    protected $customExportService;

    public function __construct(
        ManagerRegistry $managerRegistry,
        CustomExportService $customExportService,
        ?RequestStack $requestStack,
        IriConverterInterface $iriConverter,
        PropertyAccessorInterface $propertyAccessor = null,
        LoggerInterface $logger = null,
        array $properties = null,
        string $propertySearchName = 'search',
        IdentifiersExtractorInterface $identifiersExtractor = null,
        NameConverterInterface $nameConverter = null
    ) {
        parent::__construct($managerRegistry, $requestStack, $iriConverter, $propertyAccessor, $logger, $properties, $identifiersExtractor, $nameConverter);

        $this->setPropertySearchName($propertySearchName);

        $this->customExportService = $customExportService;
        $this->statements = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPropertySearchName(): string
    {
        return $this->propertySearchName;
    }

    /**
     * @param string $propertySearchName
     */
    public function setPropertySearchName(string $propertySearchName): void
    {
        $this->propertySearchName = $propertySearchName;
    }

    /**
     * @return ArrayCollection
     */
    public function getStatements(): ArrayCollection
    {
        return $this->statements;
    }

    public function apply(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ) {
        if (!isset($context['filters']) || !isset($context['filters'][$this->getPropertySearchName()])) {
            return;
        }

        $value = $context['filters'][$this->getPropertySearchName()];

        foreach ($this->properties as $property => $strategy) {
            $this->filterProperty($property, $value, $queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
        }
        $queryBuilder->andWhere(implode(' OR ', $this->getStatements()->toArray()));
    }

    public function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if (null === $value || !$this->isPropertyEnabled($property, $resourceClass) ||
            (
                !$this->isPropertyMapped($property, $resourceClass, true) &&
                !$this->customExportService->isCustomField($resourceClass, $property)
            )
        ) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $field = $property;

        if ($this->isPropertyNested($property, $resourceClass)) {
            list($alias, $field, $associations) = $this->addJoinsForNestedProperty($property, $alias, $queryBuilder, $queryNameGenerator, $resourceClass, Join::LEFT_JOIN);
            $metadata = $this->getNestedMetadata($resourceClass, $associations);
        } else {
            $metadata = $this->getClassMetadata($resourceClass);
        }

        $values = $this->normalizeValues((array) $value, $property);

        if (empty($values)) {
            $this->logger->notice('Invalid filter ignored', [
                'exception' => new InvalidArgumentException(sprintf('At least one value is required, multiple values should be in "%1$s[]=firstvalue&%1$s[]=secondvalue" format', $property)),
            ]);

            return;
        }

        $caseSensitive = true;

        if ($metadata->hasField($field) || $this->customExportService->isCustomField($resourceClass, $property)) {
            if ($metadata->hasField($field)) {
                $expression = sprintf('%s.%s', $alias, $field);

                if ('id' === $field) {
                    $values = array_map([$this, 'getIdFromValue'], $values);
                }

                if (!$this->hasValidValues($values, $this->getDoctrineFieldType($property, $resourceClass))) {
                    $this->logger->notice('Invalid filter ignored', [
                        'exception' => new InvalidArgumentException(sprintf('Value for field "%s" are not valid according to the doctrine type.', $field)),
                    ]);

                    return;
                }
            } else {
                $customFieldMetadata = $this->customExportService->getResourceRelatedFieldMetadata($resourceClass, $property);
                if (isset($customFieldMetadata[CustomExportCustomField::ATTRIBUTE_OR_SEARCH_PRE_FILTER]) &&
                    0 === preg_match($customFieldMetadata[CustomExportCustomField::ATTRIBUTE_OR_SEARCH_PRE_FILTER], $value) &&
                    \count($this->properties) > 1
                ) {
                    return;
                }

                $expression = $this->customExportService->joinAndTranslate($queryBuilder, $queryNameGenerator, $metadata->getName(), $alias, $field);
            }

            $strategy = $this->properties[$property] ?? self::STRATEGY_EXACT;

            if (0 === strpos($strategy, 'i')) {
                $strategy = substr($strategy, 1);
                $caseSensitive = false;
            }

            if (1 === \count($values)) {
                $this->addWhereByStrategy($strategy, $queryBuilder, $queryNameGenerator, $expression, $field, $values[0], $caseSensitive);

                return;
            }

            if (self::STRATEGY_EXACT === $strategy) {
                $this->logger->notice('Invalid filter ignored', [
                    'exception' => new InvalidArgumentException(sprintf('"%s"strategy selected for "%s" property, but only "%s" strategy supports multiple values', $strategy, $property, self::STRATEGY_EXACT)),
                ]);

                return;
            }

            $wrapCase = $this->createWrapCase($caseSensitive);
            $valueParameter = $queryNameGenerator->generateParameterName($field);

            $this->getStatements()
                ->add(sprintf($wrapCase('%s').' IN (:%s)', $expression, $valueParameter));
            $queryBuilder->setParameter($valueParameter, $caseSensitive ? $values : array_map('strtolower', $values));
        }

        if (!$metadata->hasAssociation($field)) {
            return;
        }

        $values = array_map([$this, 'getIdFromValue'], $values);

        if (!$this->hasValidValues($values, $this->getDoctrineFieldType($property, $resourceClass))) {
            $this->logger->notice('invalid filter ignored', [
                'exception' => new InvalidArgumentException(sprintf('Values for field "%s" are not valid according to the doctrine type.', $field)),
            ]);

            return;
        }

        $association = $field;
        $valueParameter = $queryNameGenerator->generateParameterName($association);

        if ($metadata->isCollectionValuedAssociation($association)) {
            $associationAlias = QueryBuilderHelper::addJoinOnce($queryBuilder, $queryNameGenerator, $alias, $association, Join::LEFT_JOIN);
            $associationField = 'id';
        } else {
            $associationAlias = $alias;
            $associationField = $field;
        }

        if (1 === \count($values)) {
            $this->getStatements()
                ->add(sprintf('%s.%s = :%s', $associationAlias, $associationField, $valueParameter));
            $queryBuilder->setParameter($valueParameter, $values[0]);
        } else {
            $this->getStatements()
                ->add(sprintf('%s.%s IN (:%s)', $associationAlias, $associationField, $valueParameter));
            $queryBuilder->setParameter($valueParameter, $values);
        }
    }

    protected function addWhereByStrategy(
        string $strategy,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $alias,
        string $field,
        $value,
        bool $caseSensitive
    ) {
        $wrapCase = $this->createWrapCase($caseSensitive);
        $valueParameter = $queryNameGenerator->generateParameterName($field);

        switch ($strategy) {
            case null:
            case self::STRATEGY_EXACT:
                $this->getStatements()
                    ->add(sprintf($wrapCase('%s').' = '.$wrapCase(':%s'), $alias, $valueParameter));
                $queryBuilder->setParameter($valueParameter, $value);
                break;
            case self::STRATEGY_PARTIAL:
                $this->getStatements()
                    ->add(sprintf($wrapCase('%s').' LIKE '.$wrapCase('CONCAT(\'%%\', :%s, \'%%\')'), $alias, $valueParameter));
                $queryBuilder->setParameter($valueParameter, $value);
                break;
            case self::STRATEGY_START:
                $this->getStatements()
                    ->add(sprintf($wrapCase('%s').' LIKE '.$wrapCase('CONCAT(:%s, \'%%\')'), $alias, $valueParameter));
                $queryBuilder->setParameter($valueParameter, $value);
                break;
            case self::STRATEGY_END:
                $this->getStatements()
                    ->add(sprintf($wrapCase('%s').' LIKE '.$wrapCase('CONCAT(\'%%\', :%s)'), $alias, $valueParameter));
                $queryBuilder->setParameter($valueParameter, $value);
                break;
            case self::STRATEGY_WORD_START:
                $this->getStatements()
                    ->add(sprintf($wrapCase('%1$s').' LIKE '.$wrapCase('CONCAT(:%2$s, \'%%\')').' OR '.$wrapCase('%1$s').' LIKE '.$wrapCase('CONCAT(\'%%\', :%2$s, \'%%\')'), $alias, $valueParameter));
                $queryBuilder->setParameter($valueParameter, $value);
                break;
            default:
                throw new InvalidArgumentException(sprintf('strategy %s does not exist.', $strategy));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $properties = empty($this->properties) ? 'No Properties Set' : "\n - ".implode("\n - ", array_keys($this->properties));

        return [
            $this->getPropertySearchName() => [
                'property' => $this->getPropertySearchName(),
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Return matches where any of the following properties match the search criteria: '.$properties,
                    'name' => $this->getPropertySearchName(),
                    'type' => 'string',
                ],
            ],
        ];
    }
}
