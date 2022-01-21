# Catalyst_task
Application description

PHP script is executed from the command line, which accepts a CSV file as input (see command
line directives below) and processes the CSV file. The parsed file data is inserted into a MySQL database.
A CSV file is provided that contains test data.
The PHP script handles the following criteria:

• CSV file contains user data and have three columns: name, surname, email (see table
definition below)
• CSV file has an arbitrary list of users
• Script iterates through the CSV rows and insert each record into a dedicated MySQL
database into the table “users”
• The users database table is created/rebuilt as part of the PHP script. This is
defined as a Command Line directive below
• Name and surname field is capitalised e.g. from “john” to “John” before being
inserted into DB
• Emails are set to be lower case before being inserted into DB
• The script validates the email address before inserting, to make sure that it is valid (valid
means that it is a legal email format, e.g. “xxxx@asdf@asdf” is not a legal format). In case that
an email is invalid, no insert is made to database and an error message is reported to STDOUT.



Steps to run the scripts. MySql 8.0, ubuntu 20.04. php 7.4 >

1. Setting up DB (create DB "catalyst", create user) e.g.:
CREATE DATABASE catalyst;
CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON catalyst TO 'user'@'localhost';
FLUSH PRIVILEGES;

2. users.csv file should be located in the same folder where user_upload.php file is.


3. Use following command line options to run the script

Command line options (directives):
• --file [csv file name] – this is the name of the CSV to be parsed
• --create_table – this will cause the MySQL users table to be built (and no further
• action will be taken)
• --dry_run – this will be used with the --file directive in case we want to run the script but not
	insert into the DB. All other functions will be executed, but the database won't be altered
• -u – MySQL username
• -p – MySQL password
• -h – MySQL host
• --help – which will output the above list of directives with details.
