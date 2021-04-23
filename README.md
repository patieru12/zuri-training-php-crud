
## About ZURI TRAINNIG PHP BACKEND TASK 4

### Instructions

From your knowledge of MySQL Database create a basic authentication page that does the following:

#### 1. Authentication

- register (a new user can register, their details stored in a MySQL database). 

- login (checks the database if the user is registered or not)

- reset password

- logout

#### 2. Dashboard

- Add a course.

- View all courses added by a user. 

- Edit a particular Course

- Delete a particular Course

 

NOTE: Make use of MySQL database. We're more concerned about functionalities and not design so concentrate more on functionalities.

## Setup

Please make sure the create a database name `zuri_task4_crud` in your mysql server.
### Database server information settings
Please make sure to edit `my_setting.ini` file by including required settings to connect to mysql server like

	- username example(`root`)

	- password example(`most local cases is empty`)

	- schema example (`zuri_task4_crud`)

If mysql server is running on differrent port number please remove `;` in front of port and specify the correct port number
- The script itself will populate required table definition if provided database is empty mean no table in the specified database

## Author
Ruberandinda Patience @ruberandindap 