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

namespace Drifty\model;

use Drifty\controller\mysql\mysql;
class model {
    public $table;
    public $db;
    public $fields;
    public $data;

    public function __construct()
    {
        $this->load(static::name);
    }

    /**
     * @param $name
     */
    public function load($name) {
        if ($name) {
            $this->table = $name;
            if ($this->table && $this->table !== "")
            {
                $this->db = new mysql();
                $this->fields = $this->db->fetchFields($this->table);
            }
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function retrieve(string $key, string $value): void
    {
        $this->data = $this->getData(false, array($key => $value));
    }

    public function all()
    {
        $this->data = $this->getData();
    }

    /**
     * @param false $fields
     * @param array $clauses
     * @return mixed
     */
    private function getData($fields = false, array $clauses = array()): mixed
    {
        return $this->db->select($fields ?? array('*'), $this->table, $clauses);
    }
}