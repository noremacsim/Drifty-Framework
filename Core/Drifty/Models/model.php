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

    /**
     * @var array|mixed|string|string[]
     */
    protected $tableName        = '';

    /**
     * @var array
     */
    public $properties          = array();

    /**
     * @var array
     */
    protected $parent_objects   = array();

    public function __construct()
    {
        $this->tableName = $this->getTableName();
        $this->load();
    }

    /**
     * @return array|mixed|string|string[]
     */
    public function getTableName()
    {
        if (!$this->table || $this->table === '')
        {
            if ($this->tableName == '')
            {
                $result = str_replace('Drifty\Models\\', '', get_class($this));
            }
            else
            {
                $result = $this->tableNmae;
            }
        } else {
            $result = $this->table;
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


    public function load() {
        if ($this->tableName && $this->tableName !== "")
        {
            $this->db = new mysql();
            $fields = $this->db->fetchFields($this->tableName);
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
     * @param $id
     * @return mixed
     */
    private function retrieveByPrimary($id)
    {
        return $this->db->select('*', $this->tableName, array($this->primaryKey => $id));
    }

    /**
     * @param false $fields
     * @param array $clauses
     * @return mixed
     */
    private function getData($fields = false, array $clauses = array()): string
    {
        return $this->db->select($fields ?? '*', $this->tableName, $clauses);
    }


    public function save()
    {
        //TODO: Update to use the new proporties
        $this->db->replace($this->tableName, $this->properties);
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
     * @param $propertieValues
     * @return bool|void
     */
    protected function fillPropertyValues($propertieValues)
    {
        if (!is_array($propertieValues))
        {
            return;
        }

        foreach($propertieValues as $field => $value)
        {
            if (isset($this->properties[$field]))
            {
                $this->properties[$field]['value'] = $value;
            }
        }

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

    public static function findOrCreate($id = '')
    {
        if (empty($id))
        {
            return new static;
        }

        if (is_array($id))
        {
            $object = [];
            foreach ($id as $value)
            {
                $instance = new static;
                $data = $instance->retrieveByPrimary($value);
                $instance->fillPropertyValues($data);
                $object[] = $instance;
            }
            return $object;
        } else {
            $instance       = new static;
            $data = $instance->retrieveByPrimary($id);
            $instance->fillPropertyValues($data);
            return  $instance;
        }
    }

    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object  $class
     * @return string
     */
    private function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}