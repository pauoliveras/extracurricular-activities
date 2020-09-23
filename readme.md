# Extra academic activities management

This project provides a simple solution to manage extra academic activities enrollment and assignment, based on a lucky draw number in case the available places are not enough for the applicants.

The process is divided into three steps:

## Step 1: Load participant requests

The first step is to load the participant's requests that have been registered via a dedicated form.
To do so, there is an special command that accepts this requests in CSV format:

```
php bin/console app:load-requests path/to/requests/file.csv
```

The file contents must be as follow (currently max of 5 participant requests are allowed):
```
email;candidate;group;option1;option2;option3;option4;option5
```

* **email**: participant's responsible (father/mother or legal tutor) email
* **candidate**: Candidate's name
* **group**: Candidate's group
* **option1..5**: selected activities' codes by preference priority

This process can be executed as many times as necessary case new requests are received. 

**Important**: If an existing request is loaded again, the application will thorw an exception. A code is generated for every candidate, based on candidate's name and group. The email is not used to allow registering more than one child for each.

## Step 2: Randomize candidates

Once all candidates are loaded, the next step is to assign a random number to each one of them. This number ranges from 1 to registered candidates' cardinality.

This step aims to introduce more entropy to the process.
```
php bin/console app:randomize-candidates
```

## Step 3: Generate assignments

The final step is to assign the registered candidates to the available activities. This step needs a lucky draw number, that is to be generated outside the application and provided as an argument to the assignments command:
```
php bin/console app:generate-assignments
```

