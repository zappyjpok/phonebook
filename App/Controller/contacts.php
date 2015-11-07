<?php
/**
 * Created by PhpStorm.
 * User: Shawn Legge
 * Date: 7/11/2015
 * Time: 3:54 PM
 */

require_once('../App/Library/Auth/validation.php');

class contacts extends Controller
{

    /**
     * @var array -- first name validation rules
     */
    private $firstName = [
        'empty' => 'Please provide your contact\'s first name'
    ];

    /**
     * @var array -- last name validation rules
     */
    private $lastName = [
        'empty' => 'Please provide your contact\'s last name'
    ];

    /**
     * @var array -- email validation rules
     */
    private $email = [
        'empty' => 'Please provide your contact\'s email address',
        'validEmail' => 'The email you provided is not valid',
    ];

    private $phone = [
        'empty' => 'Please provide your contact\'s phone number',
        'validPhone' => 'The phone number you provided is not valid',
    ];

    /**
     * An index page containing a list of the user's contacts
     * This page will user paigination
     *
     * @param $id -- user id
     */
    public function index($id)
    {
        $this->checkIfLoggedIn($id);


        $this->view('contact/index');
    }

    /**
     * Add a contact
     *
     * @param $id
     */
    public function create($id)
    {
        $this->checkIfLoggedIn($id);
        // token to protect against cross site attacks
        $token =$this->sessions->getToken();

        $this->view('contact/create', [
            'token' => $token
        ]);
    }

    /**
     * Validates all the values and if they are correct adds them to the database
     *
     * @param $id
     */
    public function store($id)
    {
        // get all the posted values
        $fields = $this->getFields();

        // validate the values
        $errors = $this->validation($fields);

        if(empty($errors)) {
            $message = 'Congratulations, you created an account';

            $this->setMessageCookie($message);
            $this->returnIndexPage();

        } else {
            $this->RegistrationValidationFailed('contact/create', $errors);
        }



        $this->checkIfLoggedIn($id);


    }

    public function edit($id)
    {
        $this->checkIfLoggedIn($id);

        echo 'Edit Contact';
    }

    public function destroy($id)
    {
        $this->checkIfLoggedIn($id);

        echo 'Destroy Contact';
    }

    public function test()
    {
        return var_dump($_SESSION);

    }

    private function checkIfLoggedIn($id)
    {
        if(! $this->loggedIn || $id != (int)$_SESSION['user']['user_id'])
        {
            $this->returnHomePage();
        }
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
     * Return to the home page
     */
    private function returnIndexPage()
    {
        $link = Links::action_link('contacts/index' . $_SESSION['user']['user_id']);
        header('location: ' . $link);
    }

    /**
     * Validations for the input fields
     * View = 0 -- create page
     * View = 1 -- update page
     *
     * @param $fields
     * @return array
     */
    private function validation($fields, $view = 0)
    {
        $validate = new validation();
        if(isset($fields['Fname'])) { $validate->validate($fields['Fname'], $this->firstName); }
        if(isset($fields['Lname'])) { $validate->validate($fields['Lname'], $this->lastName); }
        if(isset($fields['email'])) {  $validate->validate($fields['email'], $this->email); }
        if(isset($fields['phone'])) {  $validate->validate($fields['phone'], $this->phone); }

        $errors = $validate->getErrors();

        return $errors;
    }

    /**
     * Get's an array of values to validate
     *
     * @return array
     */
    private function getFields()
    {
        $fields = [
            'Fname' => $_POST['FirstName'],
            'Lname' => $_POST['LastName'],
            'email'     => $_POST['Email'],
            'phone'  => $_POST['Phone'],
        ];

        return $fields;
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
            'phone' => $_POST['Phone'],
            'email' => $_POST['Email']
        ]);
    }

}