<?php
/**
 * Date: 27/09/2015
 * Time: 11:24 AM
 *
 * This secure session handler class was created using a post from Edd Mann as a guide.
 * Most of the comments were added by myself in order to improve the readability of this
 * class.  For a full guide to how this class works please visit
 * http://eddmann.com/posts/securing-sessions-in-php/
 *
 */



class SecureSessionHandler extends SessionHandler {

    protected $key;

    /**
     * Change the default session name
     *
     * @var $name
     */
    protected $name;

    /**
     * Change the parameters of the cookie
     *
     * @var cookie
     */
    protected $cookie;

    /**
     * Configures sessions to comply with security best practices.  The constructor
     * sets the values of each of the desired fields
     *
     * @param $key
     * @param string $name
     * @param array $cookie
     */

    public function __construct($key, $name = 'ShoppingCart_Session', $cookie = [])
    {
        $this->key = $key;
        $this->name = $name;
        $this->cookie = $cookie;

        // These parameters can be over ridden when initialized, but they are set up
        // for best practices.  The path and domain have been over ridden in order to
        // abide by the principle of least privilege
        $this->cookie += [
            'lifetime'  => 0,
            'path'      => ini_get('session.cookie_path'),
            'domain'    => ini_get('session.cookie_domain'),
            'secure'    => isset($_SERVER['HTTPS']),
            'httponly'  => true
        ];

        $this->setup();
    }

    /**
     * Wraps the session_start, and has a 1 in 4 chance of regenerating the session ID
     *
     * @return bool
     */
    public function start()
    {
        session_start();
        session_set_cookie_params(3600 * 24 * 7);
        session_regenerate_id();
    }

    /**
     * Removes a session and forgets it's ID.  This improves security because it
     *
     * @return bool
     */
    public function forget()
    {
        if(session_id() === '')
        {
            return false;
        }

        $_SESSION = [];

        setcookie(
            $this->name, '',
            time() - 42000,
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
    }

    /**
     * Delete a single session
     *
     * @param int $name
     */
    public function destroy($name)
    {
        if(isset($_SESSION[$name]))
        {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Regenerates the session ID in order to minimize the risk of session
     * hijacking
     *
     * @return bool
     */
    private function refresh()
    {
        return session_regenerate_id(true);
    }

    /**
     * decrypts a session
     *
     * @param string $id
     * @return string
     */
    public function read($id)
    {
        return mcrypt_decrypt(MCRYPT_3DES, $this->key, parent::read($id), MCRYPT_MODE_ECB);
    }

    /**
     * encrypts a session
     *
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data)
    {
        return parent::write($id, mcrypt_encrypt(MCRYPT_3DES, $this->key, $data, MCRYPT_MODE_ECB));
    }

    /**
     * Garbage collect sessions if they get out of control
     *
     * @param int $ttl
     * @return bool
     */
    public function isExpired($ttl = 30)
    {
        $activity = isset($_SESSION['_last_activity'])
            ? $_SESSION['_last_activity']
            : false;

        if ($activity !== false && time() - $activity > $ttl * 60) {
            return true;
        }

        $_SESSION['_last_activity'] = time();

        return false;
    }

    /**
     * Checks that the IP address and user agent matches the records
     *
     *
     * @return bool
     */
    public function isFingerprint()
    {
        $hash = md5(
            $_SERVER['HTTP_USER_AGENT'] .
            (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.0.0'))
        );

        if (isset($_SESSION['_fingerprint'])) {
            return $_SESSION['_fingerprint'] === $hash;
        }

        $_SESSION['_fingerprint'] = $hash;

        return true;
    }

    public function isValid($ttl = 30)
    {
        return ! $this->isExpired($ttl) && $this->isFingerprint();
    }

    /**
     * Retrieves a session value
     *
     * @param $name
     * @return null
     */
    public function get($name)
    {
        $parsed = explode('.', $name);

        $result = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);

            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return null;
            }
        }

        return $result;
    }

    /**
     * Adds a session value
     *
     * @param $name
     * @param $value
     */
    public function put($name, $value)
    {
        $parsed = explode('.', $name);

        $session =& $_SESSION;

        while (count($parsed) > 1) {
            $next = array_shift($parsed);

            if ( ! isset($session[$next]) || ! is_array($session[$next])) {
                $session[$next] = [];
            }

            $session =& $session[$next];
        }

        $session[array_shift($parsed)] = $value;
    }

    public function push($name, $value, $time = false)
    {
        if(!isset($_SESSION[$name]) && count($_SESSION[$name]) < 1)
        {
            $_SESSION[$name] = [$value];
            if($time === true)
            {
                $_SESSION[$name . '_activation_time'] = time();
                $_SESSION['Set_once_test'] = rand(1, 100);
            }
        } else {
            array_push($_SESSION[$name], $value);
            if($time === true)
            {
                $_SESSION[$name . '_last_updated_time'] = time();
            }
        }
    }

    public function getSessionLength($name)
    {
        $length = false;

        if(isset($_SESSION[$name . '_time']))
        {
            $length = $_SESSION[$name . '_time'];
        }

        return $length;
    }

    /**
     * Sets the configuration of the sessions for the php.ini file
     */
    protected function setup()
    {
        // Configures sessions to only use cookies
        ini_set('session.use_cookies', 1);
        // Configures sessions not to use non-cookies session IDs.
        ini_set('session.use_only_cookies', 1);

        // Configures the name of the session, which will not be the default setting
        session_name($this->name);

        // sets the cookie parameters set in the php.ini file.  This will be based off the
        // values found in the constructor
        session_set_cookie_params(
            $this->cookie['lifetime'],
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
    }




}