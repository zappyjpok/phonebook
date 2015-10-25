<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 6/09/2015
 * Time: 7:19 PM
 */

require_once('../App/Library/Sessions/SecureSessionHandler.php');

/**
 * Class Sessions
 *
 * A class to help work with session
 */
class Login {

    private $logged_in = false;
    private $user_id;
    private $sessions;

    /**
     * This function will take advantage of a secure session handler
     *
     * @param $sessions
     */
    function __construct() {
        // Use the secure session
        $this->sessions = new SecureSessionHandler('login');
        $this->check_login();
    }

    /**
     * Function that checks if a user is logged in
     */
    private function check_login()
    {
        $user = $this->sessions->get('user');
        if(!is_null($user))
        {
            $this->user_id = $_SESSION['user'][0]['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }

    /**
     * Checks if the user is logged in
     *
     * @return bool
     */
    public function is_logged_in() {
        return $this->logged_in;
    }

    /**
     * If user's password and name match our records then the user is logged in
     *
     * @param $user
     */
    public function login($user) {

        if($user)
        {
            $this->sessions->push('user', [
                'user_id' => $user['useUserId'],
                'user_FirstName' => $user['useFirstName'],
                'user_LastName' => $user['useLastName'],
                'user_Email' => $user['useEmail']
            ]);
            $this->user_id = $user['useUserId'];
            $this->logged_in = true;
        }
    }

    /**
     * removes all session data and changes the value
     */
    public function logout(){
        $this->sessions->destroy('user');
        unset($this->user_id);
        $this->logged_in = false;
    }

    public function getToken()
    {
        $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        return $token;
    }
}

