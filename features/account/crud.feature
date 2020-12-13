Feature:
  Account: Create/Read/Update/List

  Scenario:
    Given I purge the database

  Scenario:
    Given I have a brokerage with the following values:
      | guid           | 6e78b7a6-2b06-4381-8773-5b29be39a26e |
      | name           | Brokerage 1                          |
      | description    | Brokerage 1                          |
      | apiDocumentUrl | http://brokerage-1.com/              |
      | context        | brokerage1                           |

    And I have an account with the following values:
      | guid              | a883ff93-cc77-40e4-840a-9bbd9cd1fced |
      | name              | Account 1                            |
      | description       | Account 1 Description                |
      | brokerage         | Brokerage 1                          |
      | accountStatusType | ACTIVE                               |
      | apiEndpointUrl    | http://url.com                       |
      | apiKey            | sda45s64213SDA                       |
      | apiSecret         | da-sdvlke1213sadsdasc2s13ad13asd     |

  Scenario: I should be able to Create a new Account (POST)

    Given the request has default headers
    When I send a "POST" request to "/accounts" with body:
    """
      {
        "brokerage": "/api/stocks/v1/brokerages/6e78b7a6-2b06-4381-8773-5b29be39a26e",
        "name": "Account 2",
        "description": "Account 2 Description",
        "apiEndpointUrl": "https://apiendpoint.com",
        "apiKey": "api-key-1223",
        "apiSecret": "dsdakjld;sknsdas",
        "context": "account2",
        "accountStatusType": "/api/stocks/v1/account_status_types/1"
      }
    """
    Then print last JSON response
    And the response status code should be 201
    And the response should be in JSON
    And the JSON node "guid" should exist
    And the JSON node "name" should be equal to "Account 2"
    And the JSON node "description" should be equal to "Account 2 Description"
    And the JSON node "apiEndpointUrl" should be equal to "https://apiendpoint.com"
    And the JSON node "apiKey" should be equal to "api-key-1223"
    And the JSON node "apiSecret" should be equal to "dsdakjld;sknsdas"
    And the JSON node "brokerage" should be equal to "/api/stocks/v1/brokerages/6e78b7a6-2b06-4381-8773-5b29be39a26e"

  Scenario: I should be able to Read all accounts (GET)
    Given the request has default headers
    When I send a "GET" request to "/accounts"
    And the response status code should be 200
    And print last JSON response
    And the JSON node "root[0].guid" should exist
    And the JSON node "root[0].name" should be equal to "Account 1"
    And the JSON node "root[0].description" should be equal to "Account 1 Description"
    And the JSON node "root[1].name" should exist
    And the JSON node "root[1].name" should be equal to "Account 2"
    And the JSON node "root[1].description" should be equal to "Account 2 Description"

  Scenario: I should be able to Read an account (GET)
    Given the request has default headers
    When I send a "GET" request to "/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced"
    And the response status code should be 200
    And the response should be in JSON

  Scenario: I should be able to Update an account (PUT)
    Given the request has default headers
    When I send a "PUT" request to "/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced" with body:
    """
      {
        "username": "Account 1 Updated",
        "description": "Account 1 Description Updated"
      }
    """
    And the response status code should be 200
    And print last JSON response
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | name              | Account 1                                                      |
      | brokerage         | /api/stocks/v1/brokerages/6e78b7a6-2b06-4381-8773-5b29be39a26e |
      | description       | Account 1 Description Updated                                  |
      | accountStatusType | /api/stocks/v1/account_status_types/1                          |

  Scenario: I should be able to Delete an account (DELETE)
    Given the request has default headers
    When I send a "DELETE" request to "/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced"
    And the response status code should be 204