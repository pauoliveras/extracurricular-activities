Feature: Load requests from file
  In order to load participants in the system
  As a user
  I need a mechanism to load  participants' requests into the application

  Scenario: Load data from csv
    Given a file named "var/data/tests/requests.csv" with the following content
    """
    email;candidate;group;option1;option2;option3;option4;option5
    user1@gmail.com;candidate 1;group 1;piscina;anglès;ioga;dansa;circ
    user1@gmail.com;candidate 2;group 2;ioga;anglès;piscina;circ
    user3@gmail.com;candidate 3;group 1;circ;ioga;dansa;piscina;anglès
    user4@gmail.com;candidate 4;group 2;ioga;dansa;circ
    """
    And following activities are available to request:
      | activity_code |
      | anglès        |
      | piscina       |
      | ioga          |
      | dansa         |
      | circ          |

    When load requests command is executed against file "var/data/tests/requests.csv"
    Then candidate "candidate 1|group 1" has been registered with "piscina,anglès,ioga,dansa,circ" ordered requests
    Then candidate "candidate 2|group 2" has been registered with "ioga,anglès,piscina,circ" ordered requests
    Then candidate "candidate 3|group 1" has been registered with "circ,ioga,dansa,piscina,anglès" ordered requests
    Then candidate "candidate 4|group 2" has been registered with "ioga,dansa,circ" ordered requests
