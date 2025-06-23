# PHP + Database
- PHP
- Docker
- Postgresql
- MongoDB

## 1. Modifying Documentation: Update Readme
- [ ] Check all the TODO Tasks
- [ ] Delete `TODO` mark when done modifying

## 2. Modifyuing Composer: Update `composer.json`
Change the following:
- [ ] your-username-here
- [ ] project-name-here
- [ ] add author/s
```json
"authors": [
    {
        "name": "your-username-here",
        "email": "your-email-here@gmail.com"
    },
    {
        "name": "your-username-here",
        "email": "your-email-here@gmail.com"
    }
],
```

## 3. Modifying Docker: Update `compose.yml`
Change the following:
- [ ] Change all `web-app-php`.
> Using `ctrl` + `shift` + `D`, each press in `D` will select another similar text and its not case sensetive.
- [ ] Update Database names: `MONGO_INITDB_DATABASE` & `POSTGRES_DB`
- [ ] (Optional) Can Change External ports <External Port>:<Internal Port> ex.: "27017:27017" -> "23567:27017"

### 4. Update the Checker
- [ ] `mongodbChecker.handler.php`
    - [ ] change the `27017` with your updated port with internal/external port
    > $mongo = `new MongoDB\Driver\Manager("mongodb://host.docker.internal:27017");` -> `$mongo = new MongoDB\Driver\Manager("mongodb://host.docker.internal:23567");`
- [ ] `postgreChecker.handler.php`
    - [ ] change the `5112` with your updated port with internal/external port
    > `$port = "5112";` -> `$port = "5555";`
- [ ] Spin up the project: in terminal use the command: `docker compose up` and in new cmd is `docker compose watch`
- [ ] Add the checker in any pages and wait for either of the 2:
    All working: 
    ```html
    ✅ Connected to MongoDB successfully.
    ✅ PostgreSQL Connection
    ```

    Need Debugging:
    ```html
    ❌ MongoDB connection failed: ...
    ❌ Connection Failed: ...
    ```

### 5. Installing Dependencies
In this demo we will install a environment setter dependency.
- `vlucas/phpdotenv`

format: `composer require <name of the dependencies>`

sample:
```ps
composer require vlucas/phpdotenv
```

### 6. Modifying `.env`: Update `.env`
Make sure important informations are hidden and tucked . as in testing of for the checker they should be changed from hard codded to env based

- [ ] Fill all the following data
> Restart the docker after this. both `docker compose watch` and `docker compose up`
- [ ] Change the hard coded of checkers to env based
- [ ] Create a `envSetter.util.php` code distributing all the env
- [ ] Update `mongodbChecker.handler.php` and `postgreChecker.handler.php`
    All working:
    ```html
    ✅ Connected to MongoDB successfully.
    ✅ PostgreSQL Connection
    ```

    Need Debugging:
    ```html
    ❌ MongoDB connection failed: ...
    ❌ Connection Failed: ...
    ```

### 7. Using Tools: Connecting Database to UI Database Manager
Using `Database` a tool at the tool tab manage and view your database
- [ ] Make Sure the Database is working. Go to Docker Desktop and make sure the `image` of `postgre` is green.
- [ ] In `Database` click `Create Connection`
- [ ] Select `PostgreSQL`
- [ ] Setup connection: Port, Username, Password and Database
> can be view the data in `compose.yaml`
- [ ] Click Connect and should show: `Connection Success!` then `Save`

### 8. Design Database: Creating Database formula preparation for automation
Using the GUI of database you need to formulate your data structure on how you will handle datas of your system.
in this demo we need to have a design for our users
Task: Users can be divided into group, they can login, basic information and role.

- [ ] Design a structure
- [ ] Create Base Pattern using the tool by simple selecting the database from `Database`
    - [ ] Select your <database name> ex.: `mydatabase`
    - [ ] Select `Tables` and look for the `+` sign then click it
    - [ ] Create Sample code then copy
    - [ ] Goto your `Explorer`
    - [ ] Create new file for that specific model ex.: `user.model.sql`
    - [ ] Add conditional command on your SQL code
        - [ ] between `CREATE TABLE` and `<table name>` add the following code `IF NOT EXISTS`

Task:
Create more tables for the following
- [ ] Projects
- [ ] Project ↔ User assignments
- [ ] Tasks

Just Copy the following for the `project_users.model.sql`
```sql
CREATE TABLE IF NOT EXISTS project_users (
    project_id INTEGER NOT NULL REFERENCES projects (id),
    user_id INTEGER NOT NULL REFERENCES users (id),
    role VARCHAR(50) NOT NULL,
    PRIMARY KEY (project_id, user_id)
);
```