Feature: Check
  I need to be able to see a currencies

  @javascript
  Scenario: Searching for a page that does exist
    Given I am on "/"
    Then I wait "3000" mseconds for the element "div_USD"
    Then I wait "3000" mseconds for the element "div_EURO"
    Then I see "USD" currency isn't null
    Then I see "EUR" currency isn't null