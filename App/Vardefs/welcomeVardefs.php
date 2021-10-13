<?php
## EXAMPLE -
//  NOTE: This is your table structure which will also be stored in globals for easy access.
//  It isnt required but if added can easily extend the database builder to generate/update table
//  and its fields.

$vardefs['welcome'] = array(
  'fields' => array(
      'salutation' => array(
          'name' => 'salutation',
          'label' => 'salutation',
          'type' => 'enum',
          'options' => array('mr', 'mrs', 'miss'), //TODO -> Add Global Options for repetitive field options
          'len' => '255',
          'comment' => 'Contact salutation (e.g., Mr, Ms)'
      ),
      'first_name' => array(
          'name' => 'first_name',
          'label' => 'FirstName',
          'type' => 'varchar',
          'len' => '100',
          'comment' => 'First name of the contact',
      ),
      'last_name' => array(
          'name' => 'last_name',
          'label' => 'LastName',
          'type' => 'varchar',
          'len' => '100',
          'comment' => 'Last name of the contact',
          'required' => true,
      ),
      'example' => array(
          'name' => 'example_field',
          'label' => 'Example',
          'type' => 'varchar',
          'len' => '100',
          'comment' => 'This is a further example',
          'required' => true,
          'non-db' => true,
      ),
  ),
);