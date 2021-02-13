<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use Behatch\HttpCall\HttpCallResult;
use Behatch\HttpCall\HttpCallResultPool;
use Behatch\Json\Json;

/**
 * Class ExtendedJsonContext.
 */
class ExtendedJsonContext
{
//    /**
//     * ExtendedJsonContext constructor.
//     *
//     * @param HttpCallResultPool $httpCallResultPool
//     * @param string             $evaluationMode
//     */
//    public function __construct(HttpCallResultPool $httpCallResultPool, $evaluationMode = 'javascript')
//    {
//        parent::__construct($httpCallResultPool, $evaluationMode);
//    }

    /**
     * @Then the JSON node :node should not equal the JSON node :reference
     *
     * @param $node
     * @param $reference
     *
     * @throws \Exception
     */
    public function theJsonNodeShouldEqualJsonNode($node, $reference)
    {
        $json = parent::getJson();

        $actual = $this->inspector->evaluate($json, $node);
        $reference = $this->inspector->evaluate($json, $reference);

        if ($reference === $actual) {
            throw new \AssertionError(sprintf('The node value is `%s`', json_encode($actual)));
        }
    }

    /**
     * @Then the JSON node :node should be equal to the JSON :jsonObj
     *
     * @param string $node
     * @param string $jsonObj
     *
     * @throws \Exception
     */
    public function theJsonNodeShouldBeEqualToTheJSON(string $node, string $jsonObj): void
    {
        $json = parent::getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if (isset($actual) && json_encode($actual) !== $jsonObj) {
            throw new \AssertionError(sprintf('The node value "%s" is not equal to "%s"', json_encode($actual), $jsonObj)
            );
        }
    }

    /**
     * @Then there should be an item in the array :node with :property equal to :value
     *
     * @param $node
     * @param $property
     * @param $value
     *
     * @throws \Exception
     */
    public function thereShouldBeAnItemInTheArrayWithEqualTo($node, $property, $value): void
    {
        $array = $this->getValueOfNode($node);

        foreach ($array as $index => $object) {
            $item = json_decode(json_encode($object), true);
            if ($item[$property] === $value) {
                return;
            }
        }

        $message = 'object with property "%s" and value "%s" not found in array %s';
        throw new \AssertionError(sprintf($message, $property, $value, $node));
    }

    /**
     * @Then all items in the array :node should have :property equal to :value
     *
     * @param $node
     * @param $property
     * @param $value
     *
     * @throws \Exception
     */
    public function allItemsInTheArrayShouldHaveEqualTo($node, $property, $value)
    {
        $array = $this->getValueOfNode($node);

        foreach ($array as $index => $object) {
            $item = json_decode(json_encode($object), true);
            if ($item[$property] !== $value) {
                $message = '%s[%s].%s value is "%s", expected "%s"';
                throw new \AssertionError(sprintf($message, $node, $index, $property, $item[$property], $value));
            }
        }
    }

    /**
     * #Then the JSON node :node should be an empty array.
     *
     * @param $node
     *
     * @throws \Exception
     */
    public function theJsonNodeShouldBeAnEmptyArray($node)
    {
        $actual = $this->getValueOfNode($node);
        if (null !== $actual && [] !== $actual) {
            throw new \AssertionError(sprintf('The node value is `%s`', json_encode($actual)));
        }
    }

    /**
     * @param $node
     *
     * @throws \Exception
     *
     * @return array|mixed|null
     */
    public function getValueOfNode($node)
    {
        return $this->evaluate($this->getJson(), $node);
    }

    /**
     * @return HttpCallResult|null
     */
    public function getResult(): ?HttpCallResult
    {
        return $this->httpCallResultPool->getResult();
    }

    /**
     * @param HttpCallResult $callResult
     */
    public function store(HttpCallResult $callResult)
    {
        $this->httpCallResultPool->store($callResult);
    }

    /**
     * @param $value
     */
    public function updateResult($value)
    {
        $this->httpCallResultPool->getResult()->update($value);
    }

    /**
     * @return Json
     */
    public function getJson()
    {
        return parent::getJson();
    }

    /**
     * @param Json   $json
     * @param string $node
     *
     * @throws \Exception
     *
     * @return array|mixed|null
     */
    public function evaluate(Json $json, string $node)
    {
        return $this->inspector->evaluate($json, $node);
    }
}
