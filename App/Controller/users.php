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
     * @var array -- email validation rules
     */
    private $email = [
        'empty' => 'Please provide your email address',
        'validEmail' => 'The email you provided is not valid',
        'emailAvailable' => 'The email you provided has already been registered'
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

        $fields = [
            'Fname' => $_POST['FirstName'],
            'Lname' => $_POST['LastName'],
            'username'  => $_POST['Username'],
            'email'     => $_POST['Email'],
            'password'  => $_POST['Password'],
            'matchPassword' => $_POST['Confirm_Password']
        ];

        $errors = $this->validation($fields);

        if(empty($errors)) {
            User::Add($_POST['FirstName'], $_POST['LastName'], $_POST['Username'], $_POST['Email'], $_POST['Password']);
            $success = "Congratulations, you created an account";
            $this->view('home/index', [
                'success' => $success
            ]);
        } else {
            $this->view('user/create', [
                'errors' => $errors,
                'firstName' => $_POST['FirstName'],
                'lastName' => $_POST['LastName'],
                'username' => $_POST['Username'],
                'email' => $_POST['Email']
            ]);
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
        $token = $this->login->getToken();

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
        $errors = $this->validation($fields);

        //check if the token matches the session token
        // If not it could be an attack
        if($_POST['token'] === $this->sessions->get('token'))
        {
            if(!empty($errors))
            {
                $token = $this->login->getToken();
                $this->view('user/login', [
                    'errors' => $errors,
                    'token' => $token,
                    'email' => $_POST['Email']
                ]);
            } else {
                if ($user)
                {
                    $this->login->login($user);
                    $success = "You are now logged in!";
                    $this->view('home/index', [
                        'success' => $success
                    ]);
                } else {
                    $token = $this->login->getToken();
                    $error = ["Your email or password does not match our records"];
                    $this->view('user/login', [
                        'errors' => $error,
                        'email' => $_POST['Email'],
                        'token' => $token
                    ]);
                }
            }
        } else {
            $token = $this->login->getToken();
            $error = ["Something went wrong, please try again"];
            $this->view('user/login', [
                'errors' => $error,
                'email' => $_POST['Email'],
                'token' => $token
            ]);
        }
        // if there are errors return to the login screen with errors

    }

    public function logout()
    {
        $this->login->logout();
        $link = Links::action_link('home/index');
        header('location: ' . $link);
    }

    public function test()
    {

        return var_dump($_SESSION);
    }

    private function validation($fields)
    {
        $validate = new validation();
        if(isset($fields['Fname'])) { $validate->validate($fields['Fname'], $this->firstName); }
        if(isset($fields['Lname'])) { $validate->validate($fields['Lname'], $this->lastName); }
        if(isset($fields['username'])) { $validate->validate($fields['username'], $this->username); }
        if(isset($fields['email'])) {  $validate->validate($fields['email'], $this->email); }
        if(isset($fields['password'])) {  $validate->validate($fields['password'], $this->password, $fields['matchPassword']); }
        if(isset($fields['confirmEmail'])) { $validate->validate($fields['confirmEmail'], $this->emailConfirm); }
        if(isset($fields['confirmPassword'])) { $validate->validate($fields['confirmPassword'], $this->passwordConfirm); }

        $errors = $validate->getErrors();

        return $errors;
    }
}

