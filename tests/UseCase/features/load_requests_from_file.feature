Feature: Load requests from file
  In order to load participants in the system
  As a user
  I need a mechanism to load  participants' requests into the application

  Background:
    Given following activities are available to request:
      | activity_code |
      | anglès        |
      | piscina       |
      | ioga          |
      | dansa         |
      | circ          |

  Scenario: Load data from csv
    Given a file named "var/data/tests/requests.csv" with the following content
    """
    email;candidate;group;desired_activity_count;option1;option2;option3;option4;option5
    user1@gmail.com;candidate 1;group 1;1;piscina;anglès;ioga;dansa;circ
    user1@gmail.com;candidate 2;group 2;2;ioga;anglès;piscina;circ
    user3@gmail.com;candidate 3;group 1;3;circ;ioga;dansa;piscina;anglès
    user4@gmail.com;candidate 4;group 2;4;ioga;dansa;circ
    """
    When load requests command is executed against file "var/data/tests/requests.csv"
    Then candidate "candidate 1|group 1" has been registered with "piscina,anglès,ioga,dansa,circ" ordered requests
    Then candidate "candidate 2|group 2" has been registered with "ioga,anglès,piscina,circ" ordered requests
    Then candidate "candidate 3|group 1" has been registered with "circ,ioga,dansa,piscina,anglès" ordered requests
    Then candidate "candidate 4|group 2" has been registered with "ioga,dansa,circ" ordered requests

  Scenario: Load data from csv with max activities
    Given a file named "var/data/tests/requests.csv" with the following content
    """
    email;candidate;group;desired_activity_count;option1;option2;option3;option4;option5
    user1@gmail.com;candidate 1;group 1;3;piscina;anglès;ioga;dansa;circ
    user1@gmail.com;candidate 2;group 2;;ioga;anglès;piscina;circ

    """
    When load requests command is executed against file "var/data/tests/requests.csv"
    Then candidate "candidate 1|group 1" has been registered with "piscina,anglès,ioga,dansa,circ" ordered requests
    And candidate "candidate 1|group 1" wants "3" activities at most
    And candidate "candidate 2|group 2" wants all requested activities
