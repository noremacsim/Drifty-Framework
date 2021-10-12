<?php
/*
 * Drifty FrameWork by noremacsim(Cameron Sim)
 *
 * This File has been created by noremacsim(Cameron Sim) under the Drifty FrameWork
 * And will follow all the Drifty FrameWork Licence Terms which can be found under Licence
 *
 * @author     Cameron Sim <mrcameronsim@gmail.com>
 * @author     noremacsim <noremacsim@github>
 */

namespace Drifty\Models;

use Drifty\Models\mysql\mysql;

class model {
    public $db;
    const model_dir             = 'App/Models';
    protected $table_name       = '';
    public $properties          = array();
    protected $parent_objects   = array();

    public function __construct()
    {
        $this->table_name = $this->getTableName();
        $this->load();
    }

    /**
     * @return array|mixed|string|string[]
     */
    public function getTableName()
    {
        if ($this->table_name == '')
        {
            $result = str_replace('Drifty\Models\\', '', get_class($this));
        }
        else
        {
            $result = $this->table_name;
        }
        return $result;
    }

    /**
     * @param $property_or_class_name
     * @return mixed
     */
    public function __get($property_or_class_name)
    {
        if ($property_or_class_name == 'primary_key')
        {
            return $this->properties[$this->primary_key_name]['value'];
        }
        elseif (array_key_exists($property_or_class_name, $this->properties))
        {
            return $this->properties[$property_or_class_name]['value'];
        }
        elseif (array_key_exists($property_or_class_name, $this->parent_objects))
        {
            return $this->parent_objects[$property_or_class_name];
        }
        else
        {
            throw new Exception(get_class($this) . ' property/parent_object ' . $property_or_class_name . ' does not exist');
            return '';
        }
    }

    /**
     * @param $property_name
     * @param $property_value
     * @return bool
     */
    public function __set($property_name, $property_value)
    {
        if (array_key_exists($property_name, $this->properties))
        {
            if ($this->properties[$property_name]['is_protected'])
            {
                throw new Exception('Cannot set value of protected property');
                return false;
            }
            else
            {
                $this->properties[$property_name]['value'] = $property_value;
            }
        }
        else
        {
            throw new Exception(get_class($this) . ' property ' . $property_name . ' does not exist');
            return false;
        }
        return true;
    }


    /**
     * @param $name
     */
    public function load() {
        if ($this->table_name && $this->table_name !== "")
        {
            $this->db = new mysql();
            $fields = $this->db->fetchFields($this->table_name);
            if ($fields) {
                foreach ($fields as $field) {
                    $this->add_property(
                        $field['COLUMN_NAME'],
                        $field['DATA_TYPE'],
                        $field['CHARACTER_MAXIMUM_LENGTH'],
                        in_array($field['COLUMN_NAME'], $this->protected)
                    );
                }
            }
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function retrieve(string $key, string $value): void
    {
        //TODO: Update to use the new proporties
        $this->data = $this->getData(false, array($key => $value));
    }

    public function all()
    {
        //TODO: Update to use the new proporties
        $this->data = $this->getData();
    }

    /**
     * @param false $fields
     * @param array $clauses
     * @return mixed
     */
    private function getData($fields = false, array $clauses = array()): string
    {
        return $this->db->select($fields ?? '*', $this->table_name, $clauses);
    }


    public function save()
    {
        //TODO: Update to use the new proporties
        $this->db->replace($this->table, $this->data);
    }

    /**
     * @param $property_name
     * @param $data_type
     * @param string $data_length
     * @param $is_protected
     * @return bool
     */
    protected final function add_property($property_name, $data_type, $data_length = '', $is_protected)
    {
        $this->properties[$property_name] = array(
            'value'              => '',
            'data_type'          => $data_type,
            'data_length'        => $data_length,
            'is_protected'       => $is_protected,
        );

        return true;
    }

    /**
     * @return int[]|string[]
     */
    public function get_property_names()
    {
        $result = array_keys($this->properties);
        return $result;
    }
}