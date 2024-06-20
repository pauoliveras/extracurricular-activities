# Extra academic activities management

This project provides a simple solution to manage extra academic activities enrollment and assignment, based on a lucky draw number in case the available places are not enough for the applicants.

## Requirements

The application is written in PHP 7.4 and requires a MySQL database to run.

Composer package manager is required to install project dependencies

Dockerization was not implemented but it could be easily added.

The application relies on data provided by extra academic activities registration form.

Before starting, be sure to execute ``sql/clean_up.sql`` script to delete data from previous editions. This script is also useful to restart the process all over again. Note that some tables are not deleted (activities) for this latter purpose.

### Initial setup

Once the runtime environment has been set up, run the following commands:

Create a local ```.env.local``` file to define configuration parameters and variables, based on provided ```.env``` template.

Define ```DATABASE_URL``` environment var according to your local setup.

``` composer install ``` to install project dependencies.

Execute ``` php bin/console doctrine:migrations:migrate``` command to initialize the working database

To be able to execute tests, an additional schema is required. Once created, repeat steps 1 and 2 to create ```.env.test.local``` file

Define ```DATABASE_URL``` environment var according to your local setup, assigning the test database schema name.




## Design

The application is designed as follows:

In the first place it manages available activities. For each activity, the number of seats has to be provided.

Requests provided via the registration form are stored as candidates.

When a request is assigned, the candidate becomes a Participant.

And finally, those requests that could not be assigned are stored in a Waiting List.

## Tests

The application provides basic unit, integration and acceptance tests suite that are very valuable to verify it is working as expected when introducing changes. Please keep them up-to-date, your future self will thank it to you a lot!

To execute each one of them:

Unit tests (PHPUnit): ``` php bin/phpunit```

Acceptance tests (Behat): ```php vendor/bin/behat```

## Considerations

The application lacks many features and requirements that could not be implemented. However, some workarounds can be made to solve most common situations:

- Brothers that want to be assigned to activities in the same day: in this case the application assigns the same random number to them, so the chances to be assigned together are greater. To do so, the random number generation is based on unique emails, not candidates!

The process is divided into three steps:

## Step 1: Load the available activities

As soon as it is available, the list of the activities with they maximum number of participants should be provided so they can be loaded to the application's database.

This list must contain the following information:

```
id;activity code;max_participants
```

To complete this information, a new id column has to be added to the set. This id has to be a uuid (v1 is enough). They can be generated from https://www.uuidgenerator.net/#google_vignette and downloaded to a file, so they can be then pasted to the available activities csv file.

If the activity has no participant limit, set it to a high value, like 1000.
To distinguish between activities of each stage, add the corresponding suffix " - Infantil" or " - Primària". If an activity can be accessed from both stages, it is not necessary to add any suffix.


## Step 2: Load participant requests

The first step is to load the participant's requests that have been registered via a dedicated form.

Before uploading the requests from the file, they have to be "sanitized", so the values are the ones that the application expects. 

To do so, run the available script as follows:

```
./scripts/translate.sh path/to/requests/file.csv
``` 

Another important thing is that the activities' codes from that file must match the codes of the available activities provided in step 1.

Once the requests have been sanitized, execute the command that accepts this requests in CSV format:

```
php bin/console app:load-requests path/to/requests/file.csv
```

The file contents must be as follow (currently max of 3 participant requests are allowed):
```
email;candidate;group;option1;option2;option3;desired_activity_count;is_member;brothers
```

* **email**: participant's responsible (father/mother or legal tutor) email
* **candidate**: Candidate's name
* **group**: Candidate's group
* **option1..3**: selected activities' codes by preference priority

This process can be executed as many times as necessary case new requests are received. 



**Important**: If an existing request is loaded again, the application will thorw an exception. A code is generated for every candidate, based on candidate's name and group. The email is not used to allow registering more than one child for each.

## Step 3: Randomize candidates

Once all candidates are loaded, the next step is to assign a random number to each one of them. This number ranges from 1 to registered candidates' cardinality.

This step aims to introduce more entropy to the process.

```
php bin/console app:randomize-candidates
```

## Step 4: Generate assignments

The final step is to assign the registered candidates to the available activities. This step needs a lucky draw number, that is to be generated outside the application and provided as an argument to the assignments command:

```
php bin/console app:generate-assignments [lucky_draw_number]
```

## Step 5: Export the results

In order to communicate the results, several lists are generated. Every one is based on an SQL query, and the result has to be exported to Excel.

The file sql/generacio_de_resultats_combinats.sql contains all the necessary scripts to generate each result.

### assignació_número_sorteig

Contains a list with all tghe candidates and the generated random number

### assignacions_per_activitat

Contains the list of assignments per activity

### llista_espera_per_activitat

Contains the waiting list of candidates that have not entered they preferred activities.

### places_assignades

List with every candidate's assigned activities

### seqüència_assignacions

This list is very important as it contains the sequence used by the algorithm to create the assignments. It acts both as a verification and debugging log.

### sol·licituds_per_activitat

List of candidate requests (not assignments) ordered by activity.

### sol·licituds_sense_cap_plaça_assignada

List of candidates that have not been assigned to any of the requested activities.

### total_sol·licituds_per_activitat

Summary of total requests that has received each activity.

