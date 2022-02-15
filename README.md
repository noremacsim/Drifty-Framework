# Drifty
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Drifty is a PHP lightweight framework. Originaly built for personal usage, parts have now came to gether along with a couple of libary's to manage future developemnt cycle.

# Development Notice


### Basic Welcome example in App/Controllers/welcome.php

- This is in very early stages
- Template Engine and Router will be replaced with my own custom ones. Dont build anything around them that can't be upgraded.

## Documentation
* > Please rename .env.example to .env
* > Update .env variables with your setup

Documentation is incomplete and will be regularly updated.
- Routing ([Klien Router Documentation](https://github.com/klein/klein.php))
- Controllers
  - Controllers are stored in the root. the root is /App.
  - All Controllers must extend Drifty/Controller.
  - Detailed Documentation coming soon. 
- Models
  - Models exist in the root. the root is /App
  - Model Table Name 
    ```php
    public $table = 'table_name';
    ```
    If no table name is set it will take the model name.
  - Model PrimaryKey
    ```php
    protected $primaryKey = 'id';
    ```
  - Model Protected Keys
    ```php
    protected $protected = [
        'id',
    ];
    ```
  - Model saveOrCreate
    - save model object data to the database table.
        ```php
        $modelExample::saveOrCreate(1);
        $modelExample->name = 'Drifty Example';
        $modelExample->save();
        ```
  - Find data from DB from PrimayID, Or Create a new Model Object by passing an array of ids multipe Model Objects can be returned of each record.
    - Example find record from DB.
      ```php
        $exampleModels::findOrCreate(1);
      ```
      - Example find multiple records by ID and return
      ```php
      $exampleModels::findOrCreate(array(1,2,3,4));
      ```
      - Return new model object to create new DB Entry
      ```php
      $exampleModels::findOrCreate();
      ```
- Databse
  - Database Schema/Builder Coming soon.
  - Documentation will be updated when released.
  - uses mysqli
  - Will exists in Drifty/models/mysql/databaseBuilder
- Template/View
  - Template engine documentation will be updated soon.
  - basic implemtation example can be found in the welcome controller
- Api
  - Documentation will be release on api release
- Authentication
  - User Model documentation will be added soon
- Object relational Mapping (TBI)
  - Partially ready will be released soon

## Composer Packages
List of the included composer packages within the Drifty FrameWork

- phpmailer (Email)
- Whoops (Error Handiling)
- klien (Router)

# Installing
- Run Composer Install
- Point to root (index.php)
- Define routes in Routes.
- Docker Container (Coming Soon)

Documentation Update Coming Soon...

@author     noremacsim <noremacsim@github>
