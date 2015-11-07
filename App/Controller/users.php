<?php
/**
 * Created by PhpStorm.
 * user: thuyshawn
 * Date: 1/09/2015
 * Time: 7:42 PM
 */

require_once('../App/Library/Auth/validation.php');

class users extends Controller
{
    /**
     * @var array -- first name validation rules
     */
    private $firstName = [
        'empty' => 'Please provide your first name'
    ];

    /**
     * @var array -- last name validation rules
     */
    private $lastName = [
        'empty' => 'Please provide your last name'
    ];

    /**
     * @var array -- username validation rules
     */
    private $username = [
        'empty' => 'Please provide a username',
        'usernameAvailable' => 'Your username has already been taken'
    ];

    /**
     * @var array -- username validation rules update
     */
    private $usernameUpdate = [
        'empty' => 'Please provide a username',
    ];

    /**
     * @var array -- email validation rules
     */
    private $email = [
        'empty' => 'Please provide your email address',
        'validEmail' => 'The email you provided is not valid',
        'emailAvailable' => 'The email you provided has already been registered'
    ];

    /**
     * @var array -- email validation rules for update
     */
    private $emailUpdate = [
        'empty' => 'Please provide your email address',
        'validEmail' => 'The email you provided is not valid',
    ];

    /**
     * @var array -- password validation rules
     */
    private $password = [
        'empty' => 'Please provide a password',
        'match' => 'Your passwords must match'
    ];

    /**
     * @var array -- login page password validation rules
     */
    private $emailConfirm = [
        'empty' => 'Please provide your email'
    ];

    /**
     * @var array -- login page password validation rules
     */
    private $passwordConfirm = [
         'empty' => 'Please provide a password'
    ];

    /**
     * returns the registration page
     */
    public function index()
    {
        $this->view('user/create');
    }

    /**
     * Returns the registration view
     */
    public function register()
    {
        $this->view('user/create');
    }

    /**
     * Function to check and input the registration process
     */
    public function store()
    {
        $this->model('User');

        $fields = $this->getFields();

        $errors = $this->validation($fields);

        if(empty($errors)) {
            User::Add($_POST['FirstName'], $_POST['LastName'], $_POST['Username'], $_POST['Email'], $_POST['Password']);
            $message = 'Congratulations, you created an account';

            $this->setMessageCookie($message);
            $this->returnHomePage();

        } else {
            $this->RegistrationValidationFailed('user/create', $errors);
        }
    }

    /**
     * Returns the login mage
     */
    public function login()
    {
        if ($this->loggedIn) {
            $link = Links::action_link('home/index');
            header('location: ' . $link);
        }
        $token = $this->sessions->getToken();

        $this->view('user/login', [
            'token' => $token
        ]);
    }

    public function check()
    {
        $this->model('User');
        $user = User::authenticate($_POST['Email'], $_POST['Password']);

        // An array of fields to be check
        $fields = [
            'confirmEmail' => $_POST['Email'],
            'confirmPassword'   => $_POST['Password']
        ];
        // Error messages
        $errors = $this->validation($fields, 2);

        //check if the token matches the session token
        // If not it could be an attack
        if($_POST['token'] === $this->sessions->get('token'))
        {
            if(!empty($errors))
            {
                $this->failedLogin($errors);
            } else {
                if ($user)
                {
                    $this->login->login($user);
                    $message = "You are now logged in!";
                    $this->successAction($message);
                } else {
                    $error = ["Your email or password does not match our records"];
                    $this->failedLogin($error);
                }
            }
        } else {

            $error = ["Something went wrong, please try again"];
            $this->failedLogin($error);
        }
        // if there are errors return to the login screen with errors

    }

    public function edit()
    {
        //User account
        $userSession = $this->sessions->get('user');
        $this->model('User');
        $user = User::find($userSession['user_id']);

        $this->view('user/update', [
            'firstName' => $user['useFirstName'],
            'lastName' => $user['useLastName'],
            'username' => $user['useUserName'],
            'email' => $user['useEmail']
        ]);

    }

    public function update()
    {
        $this->model('User');
        $user = $this->sessions->get('user');

        $fields = $this->getFields();

        $errors = $this->validation($fields, 1);

        if(empty($errors)) {
            User::edit($user['user_id'], $_POST['FirstName'], $_POST['LastName'], $_POST['Username'], $_POST['Email']);
            $this->successAction('Your account has been updated');

        } else {
            $this->RegistrationValidationFailed('user/update', $errors);
        }
    }

    public function test()
    {
        echo var_dump($_SESSION);
    }

    public function logout()
    {
        $this->login->logout();
        $link = Links::action_link('home/index');
        header('location: ' . $link);
    }


    /**
     * Redirects to the home screen with a success message
     *
     * @param $view
     * @param $message
     */
    private function successAction($message)
    {
        $this->setMessageCookie($message);
        $this->returnHomePage();
    }

    /**
     * Returns the view with posted valies
     *
     * @param $view
     * @param $errors
     */
    private function RegistrationValidationFailed($view, $errors)
    {
        $this->view($view, [
            'errors' => $errors,
            'firstName' => $_POST['FirstName'],
            'lastName' => $_POST['LastName'],
            'username' => $_POST['Username'],
            'email' => $_POST['Email']
        ]);
    }

    /**
     * Validations for the input fields
     * View = 0 -- registration page
     * View = 1 -- update page
     * View = 2 -- login page
     *
     * @param $fields
     * @return array
     */
    private function validation($fields, $view = 0)
    {
        $validate = new validation();
        if(isset($fields['Fname'])) { $validate->validate($fields['Fname'], $this->firstName); }
        if(isset($fields['Lname'])) { $validate->validate($fields['Lname'], $this->lastName); }
        if($view === 0) {
            if(isset($fields['email'])) {  $validate->validate($fields['email'], $this->email); }
            if(isset($fields['password'])) {  $validate->validate($fields['password'], $this->password, $fields['matchPassword']); }
            if(isset($fields['username'])) { $validate->validate($fields['username'], $this->username); }
        } else if ($view === 1) {
            if(isset($fields['email'])) {  $validate->validate($fields['email'], $this->emailUpdate); }
            if(isset($fields['username'])) { $validate->validate($fields['username'], $this->usernameUpdate); }
        } else if ($view === 2) {
            if(isset($fields['confirmEmail'])) { $validate->validate($fields['confirmEmail'], $this->emailConfirm); }
            if(isset($fields['confirmPassword'])) { $validate->validate($fields['confirmPassword'], $this->passwordConfirm); }
        }

        $errors = $validate->getErrors();

        return $errors;
    }

    /**
     * return fields to be checked
     *
     * @return array
     */
    private function getFields()
    {
        $fields = [
            'Fname' => $_POST['FirstName'],
            'Lname' => $_POST['LastName'],
            'username'  => $_POST['Username'],
            'email'     => $_POST['Email'],
            'password'  => $_POST['Password'],
            'matchPassword' => $_POST['Confirm_Password']
        ];
        return $fields;
    }

    private function failedLogin($message)
    {
        $token = $this->login->getToken();
        $this->view('user/login', [
            'errors' => $message,
            'email' => $_POST['Email'],
            'token' => $token
        ]);
    }

    /**
     * Return the user to the homepage
     */
    private function returnHomePage()
    {
        $link = Links::action_link('home/index');
        header('location: ' . $link);
    }

    /**
     * Set a message for the user
     *
     * @param $message
     */
    private function setMessageCookie($message)
    {
        setcookie('success', $message, time() + 3, '/');
    }
}

