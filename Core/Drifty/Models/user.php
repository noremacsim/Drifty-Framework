<?php
namespace Drifty\Models;

class user extends model {

    protected $cookieName = "userlogin";
    protected $keepCookieDays = 1;
    public $table   = 'users';
    protected $primaryKey = 'id';

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

        if ($this->properties['id']) {
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
        $encryption_iv = '32051505971505';
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
        $decryption_iv = '32051505971505';
        return openssl_decrypt($string, $ciphering, $key, $options, $decryption_iv);
    }

    /**
     * @param string $username
     * @param string $password
     * @param int $remember
     * @return bool
     */
    function login(string $username = "", string $password = "", int $remember = 0) {

        if ($username && $password) {
            $password = $this->encrypt($password, $username);
            $row = $this->sql->select("id", "users", array("username" => $username, "password" => $password));
            if (count($row)) {
                $this->find($row[0]['id']);
                $this->updateSession();
                $_SESSION['cookie'] = md5(md5(uniqid(rand(), true)));

                $this->cookie   = $_SESSION['cookie'];
                $this->session  = session_id();
                $this->ip       = $_SERVER['REMOTE_ADDR'];
                $this->save();

                $this->updateCookie($_SESSION['cookie'], !$remember);
                return true;
            }
        }
        $this->resetSession();
        return false;
    }

    /**
     * @return bool
     */
    function updateSession() {
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
    function updateCookie($key, $sessionCookie = false) {

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
    function checkCookie($cookie) {

        list($username, $cookie) = preg_split("/\|/", $_COOKIE[$this->cookieName]);

        if ($username and $cookie) {
            $result = $this->sql->select("id", "users", array('username' => $username, 'cookie' => $cookie, 'ip' => $_SERVER['REMOTE_ADDR']));
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
    function checkSession() {

        $username = $_SESSION['username'];

        if ($username) {
            $result = $this->sql->select("id", "users", array('username' => $username, "session" => session_id(), "ip" => $_SERVER['REMOTE_ADDR']));
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
