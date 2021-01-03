Feature:

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

    And I have a source with the following values:
      | guid        | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | name        | Source 1                             |
      | description | Source 1 Description                 |
      | sourceType  | Algorithm                            |

    And I have a user with the following values:
      | guid        | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | name        | user_1                               |
      | email       | user_1@stocksapi.com                 |
      | description | user 1 description                   |
      | phone       | 813-545-3290                         |

    Given I have a job with the following values:
      | guid        | 6e78b7a6-2b06-4381-8773-5b29be39a26e |
      | name        | job1                                 |
      | description | job1 description                     |
      | account     | a883ff93-cc77-40e4-840a-9bbd9cd1fced |
      | source      | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | user        | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | status      | CREATED                              |

    Given I have a job with the following values:
      | guid        | 6e78b7a6-2b06-4381-8773-5b29be39a26e |
      | name        | job2                                 |
      | description | job2 description                     |
      | account     | a883ff93-cc77-40e4-840a-9bbd9cd1fced |
      | source      | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | user        | edcfb6cb-1138-42cf-a13b-2b0fae2e4189 |
      | status      | COMPLETE                             |

    Given I have a jobItem with the following values:
      | guid   | 93f9e688-1813-45cf-8be0-7b8561e19252 |
      | data   | job data                             |
      | status | PROCESSING                           |
      | job    | 6e78b7a6-2b06-4381-8773-5b29be39a26e |

    Given I have a jobItem with the following values:
      | guid   | a8e72bef-b3fd-4691-bf44-91f7206a1e13 |
      | data   | job data                             |
      | status | PROCESSING                           |
      | job    | 6e78b7a6-2b06-4381-8773-5b29be39a26e |

    Given I have a jobItem with the following values:
      | guid   | 756055d6-4597-4f8b-84f5-60523f7f832f |
      | data   | job data                             |
      | status | Complete                             |
      | job    | 6e78b7a6-2b06-4381-8773-5b29be39a26e |

  Scenario: I should not be able to Create a new Job (POST)
    Given the request has default headers
    When I send a "POST" request to "/jobs" with body: 
      """
        {
          "name": "job3".
          "description": "job3 description",
          "account": "/api/stocks/v1/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced",
          "source": "/api/stocks/v1/sources/edcfb6cb-1138-42cf-a13b-2b0fae2e4189",
          "user": "/api/stocks/v1/users/edcfb6cb-1138-42cf-a13b-2b0fae2e4189",
          "status": "CREATED"
        }
      """
    Then print last JSON response
    And the response status code should be 401

  Scenario: I should be able to Read all jobs (GET):
    Given the request has default headers
    When I send a "GET" request to "/jobs"
    Then print last JSON response
    And the response status code should be 200
    And the JSON node "root" should have 2 elements
    And the JSON nodes should be equal to:
      | root[0].name | job1 |
      | root[1].name | job2 |

  Scenario: I should be able to Read a job (GET)
    Given the request has default headers
    When I send a "GET" request to "/jobs/6e78b7a6-2b06-4381-8773-5b29be39a26e"
    Then print last JSON response
    And the response status code should be 200
    And the JSON nodes should be equal to:
      | name        | job1                                                         |
      | description | job1 description                                             |
      | status      | CREATED                                                      |
      | account     | /api/stocks/v1/accounts/a883ff93-cc77-40e4-840a-9bbd9cd1fced |
      | source      | /api/stocks/v1/sources/edcfb6cb-1138-42cf-a13b-2b0fae2e4189" |
      | user        | /api/stocks/v1/users/edcfb6cb-1138-42cf-a13b-2b0fae2e4189    |

  Scenario: I should be able to Delete an account (DELETE)
    Given the request has default headers
    When I send a "DELETE" request to "/jobs/6e78b7a6-2b06-4381-8773-5b29be39a26e"
    Then the response status code should be 204