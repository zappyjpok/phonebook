<?php
/**
 * Created by PhpStorm.
 * User: Shawn Legge
 * Date: 7/11/2015
 * Time: 3:54 PM
 */

require_once('../App/Library/Auth/validation.php');
require_once('../App/Library/Output/pagination.php');
require_once('../App/Library/Files/UploadImage.php');
require_once('../App/Library/Files/ResizeImage.php');

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
        'validPhone' => 'The phone number you provided is not valid.  A phone number should resemble xx-xxxx-xxxx',
    ];

    /**
     * @var -- messages from the upload file class
     */
    private $uploadMessages;
    private $noUploadErrors;

    /**
     * An index page containing a list of the user's contacts
     * This page will user paigination
     *
     * @param $id -- user id
     */
    public function index($page = 1)
    {
        $this->checkIfLoggedIn();
        $this->model('Contact');

        $contacts = Contact::All($_SESSION['user']['user_id']);

        // The pagination class puts the data into chunks of 10
        $paginated = new pagination($contacts, 10);
        $contactsPerPage = $paginated->createPagination();
        $count = $paginated->getRows() / 10;

        $this->view('contact/index', [
            'contacts' => $contactsPerPage[$page -1],
            'pages' => $count,
            'page'  => $page
        ]);
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
    public function store()
    {
        // Check if logged in
        $this->checkIfLoggedIn();

        // Include the model
        $this->model('Contact');

        // get all the posted values
        $fields = $this->getFields();

        // validate the values
        $errors = $this->validation($fields);

        // Variables needed
        $max = 500 * 1024; //size of the image
        $destination =  'images/contacts';

        if (!empty($_FILES['Image']['name']))
        {
            $file = $this->uploadFile($destination, $max);

            $this->sessions->put('upload', $this->uploadMessages);
        }

        $link = Links::action_link('contacts/test');
        header('location: ' . $link);

        /*
        if(empty($errors)) {
            $message = 'Congratulations, you created an account';
            Contact::Add($_SESSION['user']['user_id'], $_POST['FirstName'], $_POST['LastName'], $_POST['Email'], $_POST['Phone']);
            $this->setMessageCookie($message);
            $this->returnIndexPage();

        } else {
            $this->RegistrationValidationFailed('contact/create', $errors);
        }
        */




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
        //unset($_SESSION['upload']);
        $destination =  'images/contacts';
        $message = '';

        if (!is_dir($destination))
        {
            $message = 'There is no directory';
        } else {
            if(is_writable($destination))
            {
                $message = 'The folder is writable';
            } else {
                $message = 'The folder is not writable';
            }
        }

        echo '<pre>';
            print_r($_SESSION);
        echo "</pre>";


    }

    private function checkIfLoggedIn()
    {
        if(! $this->loggedIn)
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
        $link = Links::action_link('contacts/index');
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

    /**
     * Upload a file
     *
     * @param $destination
     * @param $max
     * @return string
     * @throws \Exception
     */
    private function uploadFile($destination, $max)
    {
        try {
            $upload = new UploadImage($destination);
            $upload->setMaxSize($max);
            $upload->upload();
            $results = $upload->getMessages();
            $this->noUploadErrors= $upload->checkErrors();
        } catch (Exception $e) {
            $results = $e->getMessage();
        }

        // This does not work with multiple files, but its a start: Need to change to array
        if($this->noUploadErrors== true)
        {
            $this->sessions->put('uploadSuccess', $this->uploadMessages);
        } else {
            $this->sessions->put('uploadError', $this->uploadMessages);
        }

        // Collecting the data to save into the table
        //$fileName = $upload->getName(current($_FILES));
        //$file = $destination . '/' . $fileName;

        $file = '';

        return $file;
    }

}