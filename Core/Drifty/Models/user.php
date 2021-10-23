<?php
namespace Drifty\Models;

class user extends model {

    /**
     * @var string
     */
    public $table   = 'users';

    /**
     * @var string[]
     */
    protected $protected = [
        'id',
    ];

    /**
     * @var string
     */
    protected $cookieName = "userlogin";

    /**
     * @var int
     */
    protected $keepCookieDays = 1;

    /**
     * @var string
     */
    protected $primaryKey = 'id';


    public function __construct() {
        parent::__construct();
        $this->checkStatus();
    }

    /**
     * @return bool
     */
    public function checkStatus()
    {

        if ($this->checkSession() === false) {
            if (isset($_COOKIE[$this->cookieName])) {
                $this->checkCookie();
           }
        }

        if (!empty($this->properties['id']['value'])) {
            $this->lastactive = date('Y-m-d H:i:s');
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function resetSession()
    {
        setcookie($this->cookieName, "", "-1", '/');
        $_SESSION['logged']	    = false;
        $_SESSION['username']	= "";
        $_SESSION['cookie']	    = "";
        $_SESSION['remember']	= false;

        global $user;
        $user = new static;
        return true;
    }

    /**
     * @param $string
     * @param $key
     * @return false|string
     */
    public function encrypt($string, $key) {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption_iv = '3205150597150567';
        return openssl_encrypt($string, $ciphering, $key, $options, $encryption_iv);
    }

    /**
     * @param $string
     * @param $key
     * @return false|string
     */
    public function decrypt ($string, $key) {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $decryption_iv = '3205150597150567';
        return openssl_decrypt($string, $ciphering, $key, $options, $decryption_iv);
    }

    /**
     * @param string $username
     * @param string $password
     * @param int $remember
     * @return bool
     */
    public function login(string $username = "", string $password = "", int $remember = 0)
    {

        if ($username && $password) {
            $password = $this->encrypt($password, $username);
            $row = $this->db->select("id", "users", array("username" => $username, "password" => $password));
            if (count($row)) {
                $this->find($row[0]['id']);
                $this->updateSession();
                $_SESSION['cookie'] = md5(md5(uniqid(rand(), true)));
                //$this->cookie   = $_SESSION['cookie'];
                $this->session = session_id();
                $this->ip = $_SERVER['REMOTE_ADDR'];
                $this->remember = 0;
                $this->save();
                $this->updateCookie($_SESSION['cookie'], !$remember);
                return true;
            }
        }
        $this->resetSession();
        return false;
    }

    //TODO: Test And finish Function
    /**
     * @param $registrationDetails
     * @return false
     */
    public function register($registrationDetails)
    {
        $this->resetSession();
        //TODO: Update tis with on save?
        if ($registrationDetails['username'] && $registrationDetails['password'])
        {
            $this->password = $this->encrypt($registrationDetails['password'], $registrationDetails['username']);
            $this->session = session_id();
            $this->ip = $_SERVER['REMOTE_ADDR'];
            $this->remember = 0;
            $this->username = $registrationDetails['username'];
            $this->email = $registrationDetails['email'];
            $this->saveOrCreate();

        }
        $this->resetSession();
        return false;
    }

    /**
     * @return bool
     */
    public function updateSession() {
        if (is_array($this->properties) && !empty($this->properties[$this->primaryKey]['value'])) {

            $values = $this->properties;
            foreach (array('password', 'cookie', 'resetcode', 'session') as $key) {
                unset($values[$key]);
            }

            $_SESSION['username']		    = $values['username']['value'];
            $_SESSION['logged']			    = true;
            $_SESSION['currentuser']        = $values;
        }

        return true;
    }

    /**
     * @param $key
     * @param false $sessionCookie
     * @return bool
     */
    public function updateCookie($key, $sessionCookie = false) {

        if ($sessionCookie == false) {
            $cookieLife = time() + (86400 * $this->keepCookieDays);
        }

        $cookie = sprintf("%s|%s", $_SESSION['username'], $key);
        setcookie($this->cookieName, $cookie, $cookieLife, '/');

        return true;
    }

    /**
     * @param $cookie
     * @return bool
     */
    public function checkCookie() {
        //TODO: Check the security around this
        list($username, $cookie) = preg_split("/\|/", $_COOKIE[$this->cookieName]);

        if ($username and $cookie) {
            $result = $this->db->select("id", "users", array('username' => $username, 'cookie' => $cookie, 'ip' => $_SERVER['REMOTE_ADDR']));
            if (count($result) == 1) {
                $this->find($result[0]['id']);
                $this->updateSession();
                return true;
            }
        }

        $this->resetSession();
        return false;
    }

    /**
     * @return bool
     */
    public function checkSession() {

        $username = $_SESSION['username'];

        if ($username) {
            $result = $this->db->select("id", "users", array('username' => $username, "session" => session_id(), "ip" => $_SERVER['REMOTE_ADDR']));
            if (count($result) == 1) {
                $this->find($result[0]['id']);
                $this->updatesession();
                return true;
            }
        }

        $this->resetSession();
        return false;
    }
}
