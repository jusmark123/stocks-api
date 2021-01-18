Feature:
  Account: Create/Read/Update/List

  @loadBrokerageFixtures
  Scenario: Load fixtures
    And I have an "Alpaca" account with the following values:
      | guid              | a883ff93-cc77-40e4-840a-9bbd9cd1fced     |
      | name              | Account 1                                |
      | description       | Account 1 Description                    |
      | brokerage         | Alpaca Trader                            |
      | accountStatusType | ACTIVE                                   |
      | apiKey            | PKU2P6NISHZELU5ATWEQ                     |
      | apiSecret         | yACk0pUFDlBQRBrbktzTe5iODUumrQACriY7TSK3 |
      | apiEndpointUrl    | https://paper-api.alpaca.markets         |
      | default           | true                                     |

  Scenario: I should be able to Create a new Account (POST)

    Given the request has default headers
    When I send a "POST" request to "/accounts" with body:
    """
      {
        "brokerage": "/api/stocks/v1/brokerages/9e13594c-0172-45b4-a9db-ed11db638601",
        "name": "Account 2",
        "description": "Account 2 Description",
        "apiEndpointUrl": "https://paper-api.alpaca.markets",
        "apiKey": "PKU2P6NISHZELU5ATWEQ",
        "apiSecret": "yACk0pUFDlBQRBrbktzTe5iODUumrQACriY7TSK3",
        "context": "account2",
        "default": false,
        "accountStatusType": "/api/stocks/v1/account_status_types/1"
      }
    """
    Then print last JSON response
    And the response status code should be 201
    And the response should be in JSON
    And the JSON node "guid" should exist
    And the JSON node "name" should be equal to "Account 2"
    And the JSON node "description" should be equal to "Account 2 Description"
    And the JSON node "apiEndpointUrl" should be equal to "https://paper-api.alpaca.markets"
    And the JSON node "apiKey" should be equal to "PKU2P6NISHZELU5ATWEQ"
    And the JSON node "apiSecret" should be equal to "yACk0pUFDlBQRBrbktzTe5iODUumrQACriY7TSK3"
    And the JSON node "brokerage" should be equal to "/api/stocks/v1/brokerages/9e13594c-0172-45b4-a9db-ed11db638601"
    And the JSON node "default" should be false
    And the JSON node "accountInfo" should exist

  Scenario: I should be able to Read all accounts (GET)
    Given the request has default headers
    When I send a "GET" request to "/accounts"
    And the response status code should be 200
    And print last JSON response
    And the JSON nodes should be equal to:
      | root[0].name        | Account 1             |
      | root[0].description | Account 1 Description |
      | root[1].name        | Account 2             |
      | root[1].description | Account 2 Description |

  Scenario: I should be able to Read an account (GET)
    Given the request has default headers
    When I send a "GET" request to "/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced"
    And the response status code should be 200
    And the response should be in JSON
    And the JSON nodes should be equal to:
      | name              | Account 1                                                      |
      | brokerage         | /api/stocks/v1/brokerages/9e13594c-0172-45b4-a9db-ed11db638601 |
      | description       | Account 1 Description                                          |
      | accountStatusType | /api/stocks/v1/account_status_types/1                          |

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
      | brokerage         | /api/stocks/v1/brokerages/9e13594c-0172-45b4-a9db-ed11db638601 |
      | description       | Account 1 Description Updated                                  |
      | accountStatusType | /api/stocks/v1/account_status_types/1                          |

  Scenario: I should be able to Delete an account (DELETE)
    Given the request has default headers
    When I send a "DELETE" request to "/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced"
    And the response status code should be 204