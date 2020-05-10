Feature: Randomize candidate
  In order to ensure the lucky draw is random
  As a system
  I need to assign a random number to each candidate

  Scenario: Assign a random number to each candidate
    Given following activities are available to request:
      | activity_code |
      | anglès        |
      | piscina       |
      | ioga          |
      | dansa         |
      | circ          |
    And "15" candidates have placed a request to any of this activities "ioga, piscina, circ, dansa, anglès"
    When randomize candidates command is executed
    Then every candidate has a been assigned a unique number between "1" and "15"
