<?php

namespace Drifty\model;

use Drifty\controller\mysql\mysql;
class model {
    public $db;

    public function __construct() {
        $this->db = new mysql();
    }
}