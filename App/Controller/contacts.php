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

    private $uploadSuccessMessage;
    private $uploadErrorMessages;

    /**
     * @var -- file names to place in the database
     */
    private $files = [];

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

        $token = $this->sessions->getToken();

        $this->view('contact/index', [
            'contacts' => $contactsPerPage[$page -1],
            'pages' => $count,
            'page'  => $page,
            'token' => $token
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
        $this->model('Image');

        // get all the posted values
        $fields = $this->getFields();

        // validate the values
        $errors = $this->validation($fields);

        // Variables needed
        $max = 500 * 1024; //size of the image
        $destination =  'images/contacts';

        if (!empty($_FILES['Image']['name']))
        {
            // The name of the file or files uploaded
            $this->uploadFile($destination, $max);

            if(!empty($this->uploadErrorMessages)  || !empty($errors))
            {
                $allErrors = array_merge($this->uploadErrorMessages, $errors);
                $this->ValidationFailed('contact/create', $allErrors);

            } else {
                $this->updateImages('Congratulations, you created an account');
            }

        }
    }

    public function edit()
    {
        $this->checkIfLoggedIn();

        echo 'Edit Contact';

    }

    public function destroy()
    {
        $this->checkIfLoggedIn();

        // Get all images associated with the contact
        $this->model('Image');
        $this->model('Contact');
        $images = Image::All($_POST['ID']);

        // Delete all images
        foreach($images as $image)
        {
            unlink($image['imgPath']);
            unlink(Links::changeToThumbnail($image['imgPath']));
        }
        // Delete Contact
        Contact::delete($_POST['ID']);
        $this->returnIndexPage();
    }

    /**
     * Users select what image is the main image
     */
    public function selectImage()
    {
        $this->checkIfLoggedIn();
        // Get the id
        $id = $this->sessions->withdrawl('contact_id');

        // Check if was redirected from the store
        $token = $this->sessions->withdrawl('token');
        $cookieToken = $_COOKIE['token'];


        if($token !== $cookieToken && is_null($cookieToken))
        {
            $this->returnIndexPage();
        } else {
            // Get all images
            $this->model('Image');
            $images = Image::All($id);
            // Get token for new form
            $token = $this->sessions->getToken();

            $this->view('contact/selectImage', [
                'id'    => $id,
                'images' => $images,
                'token' => $token
            ]);
        }
    }

    public function imageStore()
    {
        $this->checkIfLoggedIn();
        if(!is_null($_POST['radio']))
        {
            if($this->sessions->withdrawl('token') === $_POST['token'])
            {
                $this->model('Image');
                Image::AddMain($_POST['ID'], $_POST['radio']);

                $this->setMessageCookie('You have selected your image');
                $this->returnIndexPage();
            }
        } else {
            $this->model('Image');
            $images = Image::All($_POST['ID']);
            $token = $this->sessions->getToken();
            $errors = ['Please select an image'];

            $this->view('contact/selectImage', [
                'errors' => $errors,
                'id'    => $_POST['ID'],
                'images' => $images,
                'token' => $token
            ]);
        }
    }


    public function test()
    {

    }

    private function checkIfLoggedIn()
    {
        if(! $this->loggedIn)
        {
            $this->returnHomePage();
        }
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
    private function ValidationFailed($view, $errors)
    {
        $token = $this->sessions->getToken();
        $this->view($view, [
            'errors' => $errors,
            'token' => $token,
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
            $this->uploadErrorMessages  = $upload->getErrors();
        } catch (Exception $e) {
            $this->uploadErrorMessages = $e->getErrors();
        }

        // check if there are no errors which will determine the type of return
        if(empty($this->uploadErrorMessages))
        {
            $this->uploadSuccessMessage = $upload->getSuccess();
            // Collecting the data to save into the table
            $fileNames = $upload->getName();

            foreach($fileNames as $fileName)
            {
                $this->files [] = $destination . '/' . $fileName;
            }
        }
    }

    /**
     * uploads a file and adds them to the database
     *
     * @param $message
     */
    private function updateImages($message)
    {
        $id = Contact::Add($_SESSION['user']['user_id'], $_POST['FirstName'], $_POST['LastName'], $_POST['Email'], $_POST['Phone']);
        foreach($this->files as $file)
        {
            // Add contact ID and file name
            Image::Add($id, $file);
            // resize the files
            $resize = new \App\library\ResizeImage($file, 400, 400);
            $resize->createResizeImage();
            $resize->createThumbNail(200, 200);
        }

        $this->setMessageCookie($message);
        $this->returnSelectImagePage($id);
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
     * Passes the ID to the next page and opens the select Image page
     *
     * @param $id
     */
    private function returnSelectImagePage($id)
    {
        // Pass the contact ID to the next page
        $this->sessions->put('contact_id', $id);
        // Create a token for the next page
        $token = $this->sessions->getToken();
        setcookie('token', $token, time() + 10, '/');

        // go to the select image page with variables
        $link = Links::action_link('contacts/selectImage');
        header('location: ' . $link);
    }

}