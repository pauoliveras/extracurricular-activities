Feature: Load requests
  In order to load participants in the system
  As a user
  I need a mechanism to load  participants' requests into the application

  Scenario: Load data from csv
    Given a list with the following requests per user
      | email           | candidate   | group   | option1 | option2 | option3 | option4 | option5 |
      | user1@gmail.com | candidate 1 | group 1 | piscina | anglès  | ioga    | dansa   | circ    |
      | user2@gmail.com | candidate 2 | group 2 | ioga    | anglès  | piscina | circ    |         |
      | user3@gmail.com | candidate 3 | group 1 | circ    | ioga    | dansa   | piscina | anglès  |
      | user4@gmail.com | candidate 4 | group 2 | ioga    | dansa   | circ    |         |         |
    When requests are loaded
    Then user of email "user1@gmail.com" ordered requested options are "piscina, anglès, ioga, dansa, circ"
    Then user of email "user2@gmail.com" ordered requested options are "ioga, anglès, piscina, circ"
    Then user of email "user3@gmail.com" ordered requested options are "circ, ioga, dansa, piscina, anglès"
    Then user of email "user4@gmail.com" ordered requested options are "ioga, dansa, circ"
