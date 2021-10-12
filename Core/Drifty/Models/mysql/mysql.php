<?PHP
/*
 * Drifty FrameWork by noremacsim(Cameron Sim)
 *
 * This File has been created by noremacsim(Cameron Sim) under the Drifty FrameWork
 * And will follow all the Drifty FrameWork Licence Terms which can be found under Licence
 *
 * @author     Cameron Sim <mrcameronsim@gmail.com>
 * @author     noremacsim <noremacsim@github>
 */

namespace Drifty\Models\mysql;

class mysql {
    private $username;
    private $password;
    private $host;
    private $database;
    private $port;
    public $connection = "";
    public $error = "";


    public function __construct()
    {
        $this->username     = getenv('DB_USERNAME');
        $this->password     = getenv('DB_PASSWORD');
        $this->host         = getenv('DB_HOST');
        $this->database     = getenv('DB_DATABASE');
        $this->port         = getenv('DB_PORT');
        $this->connect();
    }

    /**
     * @param $table
     * @return array|false
     */
    public function fetchFields($table)
    {
        $result = $this->query(sprintf("select * from information_schema.columns where table_schema = '%s' and table_name = '%s'", $this->database, $table));
        if (!$result) {
            return false;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function connect(): bool
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port);
        if ($this->connection) {
            return true;
        }

        $this->error = mysqli_error($this->connection);
        return false;
    }

    /**
     * @param $database
     * @return bool
     */
    private function switchdb($database): bool
    {

        if ($database) {
            $this->database = $database;
            mysqli_select_db($this->connection, $this->database);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function disconnect(): bool
    {

        if ($this->connection) {
            mysqli_close($this->connection);
        }

        return true;
    }

    /**
     * @param string $string
     * @return mixed
     */
    public function query($string = "")
    {

        $this->lastsql = $string;
        $result = $this->connection->query($string);

        if ($result == false) {
            $error = mysqli_error($this->connection);
            $this->error = $error;
        }

        return $result;
    }

    /**
     * @param string $string
     * @return array|mixed|string
     */
    public function escapestring($string = "")
    {
        if (is_array($string) == false) {
            $string = mysqli_real_escape_string($this->connection, stripslashes($string));
        }
        return $string;
    }

    /**
     * @param string $string
     * @return array|mixed|string
     */
    public function quote($string = "")
    {
        if (is_array($string) == false) {
            $string = "'" . $this->escapestring($string) . "'";
        }
        return $string;
    }

    /**
     * @return int|string
     */
    public function getlastid()
    {
        return mysqli_insert_id($this->connection);
    }

    /**
     * @param string $table
     * @param array $values
     * @return false|int|string
     */
    public function insert($table = "", $values = array())
    {
        if ($table && is_array($values) == true && count($values) > 0) {
            $this->lastsql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $table,
                implode(", ", array_keys($values)),
                implode(", ", array_map(array($this, 'quote'),
                    array_values($values)))
            );
            $result = $this->query($this->lastsql);
            if ($result != false) {
                $insertid = $this->getlastid();
            }
        }

        return ($insertid > 0 ? $insertid : false);
    }

    /**
     * @param string $table
     * @param array $values
     * @param array $clauses
     * @return false|mixed
     */
    public function update($table = "", $values = array(), $clauses = array())
    {
        if ($table && is_array($values) == true && count($values) > 0) {

            $toupdate = array();
            foreach (array_keys($values) as $field) {
                $toupdate[] = sprintf("%s = %s", $field, $this->quote($values[$field]));
            }

            $where = array();
            if (count($clauses) > 0) {
                foreach (array_keys($clauses) as $field) {
                    $where[] = sprintf("%s = %s", $field, $this->quote($clauses[$field]));
                }
            }

            $this->lastsql = sprintf(
                "UPDATE %s SET %s WHERE %s",
                $table,
                implode(", ", $toupdate),
                implode(" AND ", $where)
            );
            $result = $this->query($this->lastsql);
        }

        return ($result ?? false);
    }

    /**
     * @param string $table
     * @param array $values
     * @param array $clauses
     * @return false|int|string
     */
    public function replace($table = "", $values = array(), $clauses = array())
    {
        $result = false;
        $check = false;

        if ($clauses == false) {
            $result = $this->insert($table, $values);
            if ($result != false) {
                $insertid = $this->getlastid();
            }

            return ($insertid > 0 ? $insertid : false);
        }

        if ($table && is_array($values) == true && count($values) > 0) {

            reset($values);
            $firstfield = key($values);

            if ($firstfield) {

                $where = array();
                if ($clauses && count($clauses) > 0) {
                    foreach (array_keys($clauses) as $field) {
                        $where[] = sprintf("%s = %s", $field, $this->quote($clauses[$field]));
                    }
                    $check = $this->query(sprintf(
                            "SELECT %s FROM %s WHERE %s",
                            $firstfield,
                            $table,
                            implode(" AND ", $where))
                    );
                }

                if (mysqli_fetch_assoc($check)) {
                    $result = $this->update($table, $values, $clauses);
                } else {
                    if ($clauses['id']) {
                        unset($clauses['id']);
                    }
                    $result = $this->insert($table, array_merge($values, $clauses));
                }
            }
        }

        if ($result != false) {
            $insertid = $this->getlastid();
        }

        return ($insertid > 0 ? $insertid : false);
    }

    /**
     * @param string $table
     * @param array $clauses
     * @return false|mixed
     */
    public function delete($table = "", $clauses = array())
    {

        if ($table && is_array($clauses) == true && count($clauses) > 0) {

            $where = array();
            if (count($clauses) > 0) {
                foreach (array_keys($clauses) as $field) {
                    $where[] = sprintf("%s = %s", $field, $this->quote($clauses[$field]));
                }
            }
            $this->lastsql = sprintf("DELETE FROM %s WHERE %s", $table, implode(" AND ", $where));
            $result = $this->query($this->lastsql);
        }

        return ($result ? $result : false);
    }

    /**
     * @param string $fields
     * @param string $table
     * @param array $clauses
     * @param string $orderby
     * @param string $groupby
     * @return array
     */
    public function select($fields = "", $table = "", $clauses = array(), $orderby = "", $groupby = "")
    {

        $rows = array();


        if ($table && (is_array($fields) == true || $fields != '')) {

            if (is_array($fields) == false) {
                $fields = array($fields);
            }

            $where = array();
            $wheresql = "";


            if (count($clauses) > 0) {
                foreach (array_keys($clauses) as $field) {
                    $where[] = sprintf("%s = %s", $field, $this->quote($clauses[$field]));
                }

                $wheresql = "WHERE " . implode(" AND ", $where);
            }

            $group = "";
            if ($groupby) {
                $group = " GROUP BY " . $groupby;
            }

            $order = "";
            if ($orderby) {
                $order = " ORDER BY " . $orderby;
            }

            $this->lastsql = sprintf("SELECT %s FROM %s %s", implode(", ", $fields), $table,
                $wheresql . $group . $order);
            $result = $this->query($this->lastsql);
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }

        }

        return $rows;
    }

    /**
     * @param string $string
     * @param string $field
     * @param string $table
     * @param array $clauses
     * @param int $flexability
     * @return mixed|void
     */
    function fuzzyselect($string = "", $field = "", $table = "", $clauses = array(), $flexability = 2)
    {

        $rows = array();
        $differences = array();

        if ($string && $field && $table) {

            $where = array();
            if (count($clauses) > 0) {
                foreach (array_keys($clauses) as $f) {
                    $where[] = sprintf("%s = %s", $f, $this->quote($clauses[$f]));
                }
            }

            $where[] = sprintf("%s LIKE %s", $field, $this->quote(substr($string, 0, 1) . "%"));

            $this->lastsql = sprintf("SELECT id, %s AS string FROM %s WHERE %s", $field, $table,
                implode(" AND ", $where));
            $result = $this->query($this->lastsql);
            while ($row = mysqli_fetch_assoc($result)) {

                $difference = levenshtein($string, $row['string']);
                if ($difference <= $flexability) {

                    $row['difference'] = $difference;

                    $rows[] = $row;
                    $differences = $difference;
                }
            }

            if (count($rows) > 0) {
                array_multisort($rows, SORT_ASC, $differences);
                return $rows[0]['id'];
            }
        }

    }

    /**
     * @param $resource
     * @return array|false|string[]|null
     */
    function fetch($resource)
    {
        return mysqli_fetch_assoc($resource);
    }

    /**
     * @param $resource
     * @return int|string
     */
    function foundrows($resource)
    {
        return mysqli_num_rows($resource);
    }

}