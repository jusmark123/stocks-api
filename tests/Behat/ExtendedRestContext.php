<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Behat\Traits\DatabaseContextTrait;
use App\Tests\Behat\Traits\MinkContextTrait;
use App\Tests\Behat\Traits\RestContextTrait;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\HttpCall\HttpCallResult;
use Doctrine\Persistence\ObjectRepository;

class ExtendedRestContext extends AbstractContext
{
    use DatabaseContextTrait;
    use MinkContextTrait;
    use RestContextTrait;

    protected $storedData = [];

    protected $result;

    /**
     * @Then save the :node from the Json response to :key
     * @Then store the :node from the Json response to :key
     *
     * @param $node
     * @param $key
     */
    public function saveTheFromTheJsonResponseTo($node, $key)
    {
        $this->storedData[$key] = $this->extendedJsonContext->theJsonNodeShouldExist($node);
    }

    /**
     * @Then the Json node :node should contain stored value :key
     *
     * @param $node
     * @param $key
     */
    public function theJsonNodeShouldContainStored($node, $key)
    {
        $savedValue = $this->storedData[$key];
        $nodeValue = $this->extendedJsonContext->theJsonNodeShouldExist($node);

        if (false === strrpos($nodeValue, $savedValue)) {
            sprintf('json node %s does not contain the stored value for "%s" ("%s" != "%s")',
                $node,
                $key,
                $savedValue,
                $nodeValue
            );
        }
    }

    /**
     * @When the Json node :node should be equal to stored value :key
     *
     * @param $node
     * @param $key
     */
    public function theJsonNodeShouldBeEqualToStored($node, $key)
    {
        $savedValue = $this->storedData[$key];
        $nodeValue = $this->extendedJsonContext->theJsonNodeShouldExist($node);

        if ($savedValue !== $nodeValue) {
            throw new \AssertionError(sprintf('json node %s does not equal stored value for "%s" ("%s" != "%s")',
                $node,
                $key,
                $savedValue,
                $nodeValue
            ));
        }
    }

    /**
     * @When I send a :method request to :endpoint with previously stored value :identifier as a path parameter
     *
     * @param $method
     * @param $endpoint
     * @param $identifier
     *
     * @return mixed
     */
    public function iSendARequestToWithPreviouslyStoredValueAsPathParameter($method, $endpoint, $identifier)
    {
        if (\array_key_exists($identifier, $this->storedData)) {
            throw new \AssertionError(sprintf('The key %s does not exist', $identifier));
        }

        $endpoint .= preg_match('/{stored}/', $endpoint) ? '' : '/{stored}';
        $endpoint = preg_replace('/{stored/', $this->storedData[$identifier], $endpoint);

        return $this->restContext->iSendARequest($method, $endpoint);
    }

    /**
     * @Then print the stored data
     */
    public function printLastJsonResponse()
    {
        echo json_encode($this->storedData);
    }

    /**
     * @when I send a :method request to :url with stored data with body:
     *
     * @param $method
     * @param $url
     * @param PyStringNode $body
     *
     * @return mixed
     */
    public function iSendARequestToWithCustomBody($method, $url, PyStringNode $body)
    {
        $newStrings = [];
        foreach ($body->getStrings() as $string) {
            $newString = $string;
            foreach ($this->storedData as $key => $value) {
                $newString = str_replace($key, $value, $newString);
            }
            $newStrings[] = $newString;
        }

        $newBody = new PyStringNode($newStrings, 0);

        echo 'New Body => '.$newBody->getRaw();

        return $this->restContext->iSendARequestTo($method, $url, $newBody);
    }

    /**
     * @Then the JSON node :node should have :count child nodes
     *
     * @param string $node
     * @param int    $count
     */
    public function theJsonNodeShouldHaveChildNodes(string $node, int $count)
    {
        $actual = $this->extendedJsonContext->theJsonNodeShouldExist($node);
        $array = json_decode(json_encode($actual), true);

        if (!\is_array($array)) {
            throw new \AssertionError(sprintf('%s is not an object', $array));
        }

        $actualCount = \count(array_keys($array));
        if ($count !== $actualCount) {
            throw new \AssertionError(sprintf('child nodes expected: %d, got %d', $count, $actualCount));
        }
    }

    /**
     * @Then the JSON node :node should be equal to GET response from :endpoint
     *
     * @param string $node
     * @param string $endpoint
     */
    public function theJsonNodeShouldBeEqualToGetResponseFrom(string $node, string $endpoint)
    {
        $this->compareJsonNodeToGetResponse($node, $endpoint);

        $this->getMinkContext()->getMink()->getSession()->back();
    }

    /**
     * @then the JSON node :node should be equal to GET response from :endpoint from prior post
     *
     * @param string $node
     * @param string $endpoint
     */
    public function theJsonNodeSHouldBeEqualToGetResponseFRomPriorPost(string $node, string $endpoint)
    {
        $this->compareJsonNodeToGetResponse($node, $endpoint);
    }

    /**
     * @Then the JSON node :node should be an empty string
     *
     * @param string $node
     */
    public function theJsonNodeShouldBeAnEmptyString(string $node): void
    {
        $actual = $this->extendedJsonContext->theJsonNodeShouldExist($node);

        if ('' !== $actual) {
            throw new \AssertionError(
                sprintf('expected %s to be empty string, got %s', $node, var_export($actual, true))
            );
        }
    }

    public function saveTheResponse(): void
    {
        $this->result = $this->extendedJsonContext->getResult();
    }

    /**
     * @return HttpCallResult|null
     */
    public function getSavedResponse(): ?HttpCallResult
    {
        return $this->result;
    }

    /**
     * @param string $entity
     * @param string $field
     * @param string $value
     *
     * @return string|null
     */
    private function getEntityIdentifier(string $entity, string $field, string $value): ?string
    {
        try {
            $manager = $this->getDatabaseContext()->getEntityManager();
            if (!$manager) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            throw new \AssertionError('Failed to get the entity manager');
        }

        try {
            $repository = $manager->getRepository($entity);
            if (!$repository instanceof ObjectRepository) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            throw new \AssertionError(sprintf('Unable to find Entity Repository for "%s"', $entity));
        }

        $result = $repository->findOneBy([$field => $value]);

        if (null === $result) {
            throw new \AssertionError(sprintf(
                'No entity found for field "%s" with value "%s"',
                $field,
                $value
            ));
        }

        try {
            if (method_exists($result, 'getGuid')) {
                return (string) $result->getGuid();
            }

            return (string) $result->getId();
        } catch (\Throwable $e) {
            throw new \AssertionError('Entity does not have a "getId()/getGuid()" function');
        }
    }

    /**
     * @param $nodeValue
     *
     * @return \DateTime|false
     */
    public function validateTimestamp($nodeValue)
    {
        try {
            $date = \DateTime::createFromFormat(DATE_ATOM, $nodeValue);
        } catch (\Exception | \TypeError $e) {
            throw new \AssertionError('Invalid Datetime format provided');
        }

        if (\is_bool($date)) {
            throw new \AssertionError('Invalid Datetime format provided');
        }

        return $date;
    }

    /**
     * @param string $node
     * @param string $endpoint
     */
    private function compareJsonNodeToGetResponse(string $node, string $endpoint): void
    {
        if (null === $this->getRestContext() || null === $this->getMinkContext()) {
            throw new \AssertionError('You must import the Context "Behatch\Context\RestContext"
                and Behat\MinkExtension\Context\MinkContext" to run this statement');
        }

        if (null !== $this->getSavedResponse()) {
            $this->extendedJsonContext->updateResult($this->getSavedResponse()->getValue());
        }

        $actualJson = $this->extendedJsonContext->getJson();
        $this->allRequestsHaveHeaders();
        $result = $this->getRestContext()->iSendARequestTo('GET', $endpoint);

        $callResult = new HttpCallResult($result->getContent());
        $this->extendedJsonContext->store($callResult);

        $expectedJson = $this->extendedJsonContext->getJson();

        $actual = $this->extendedJsonContext->evaluate($actualJson, $node);
        $expected = $this->extendedJsonContext->evaluate($expectedJson, 'root');

        if ($actual !== $expected) {
            throw new \AssertionError(sprintf(
                'The result of "%s" does not match node "%s", expected %s, gpt %s',
                $endpoint,
                $node,
                json_encode($actual, JSON_PRETTY_PRINT),
                json_encode($expected, JSON_PRETTY_PRINT)
            ));
        }
    }

    /**
     * @Given all requests have headers
     * @Given the request has default headers
     */
    public function allRequestsHaveHeaders()
    {
        $this->getRestContext()->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->getRestContext()->iAddHeaderEqualTo('Accept', 'application/json');
    }
}
