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
        $link = 'contacts/index/';

        $this->view('contact/index', [
            'contacts' => $contactsPerPage[$page -1],
            'pages' => $count,
            'page'  => $page,
            'token' => $token,
            'link'  => $link
        ]);
    }

    /**
     * Add a contact
     *
     * @param $id
     */
    public function create()
    {
        // Check if logged in
        $this->checkIfLoggedIn();

        // token to protect against cross site attacks
        $token =$this->sessions->getToken();

        $this->view('contact/create', [
            'token' => $token
        ]);
    }

    /**
     * Show contacts information
     *
     * @param $id
     */
    public function show($id)
    {
        // check if user is logged in
        $this->checkIfLoggedIn();
        $id =  Output::phpOutput($id);

        // Get models
        $this->model('Contact');
        $this->model('Image');

        // Security check make sure record belongs to user
        $belongsTo = Contact::checkContactUser($_SESSION['user']['user_id'], $id);
        $this->checkSecurityValue($belongsTo, 'contacts/index', 'Something went wrong, please try again');

        // Get contact information
        $contact = Contact::findAll($id);
        $main = Image::getMain($id);

        $this->view('contact/show', [
            'main' => $main[0]['imgPath'],
            'contact' => $contact
        ]);
    }

    public function getSearch()
    {
        $search = Output::phpOutput($_POST['search']);
        $this->redirectTo('contacts/search/' . $search);
    }

    public function search($search, $page = 1)
    {
        // check if user is logged in
        $this->checkIfLoggedIn();
        $search = Output::phpOutput($search);

        // Get models
        $this->model('Contact');
        $contacts = Contact::search($search);

        // The pagination class puts the data into chunks of 10
        $paginated = new pagination($contacts, 10);
        $contactsPerPage = $paginated->createPagination();
        $count = $paginated->getRows() / 10;

        $token = $this->sessions->getToken();
        $link = 'contacts/search/' . $search . '/';

        $this->view('contact/search', [
            'contacts' => $contactsPerPage[$page -1],
            'pages' => $count,
            'page'  => $page,
            'token' => $token,
            'link'  => $link
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

        // Check if tokens match
        $this->checkTokensMatched($this->sessions->withdrawl('token'), Output::phpOutput($_POST['token']));

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


        // The name of the file or files uploaded
        $this->uploadFile($destination, $max);

        if(!empty($this->uploadErrorMessages)  || !empty($errors))
        {
            $allErrors = array_merge($this->uploadErrorMessages, $errors);
            $this->ValidationFailed('contact/create', $allErrors);

        } else {
            $id = Contact::Add(Output::phpOutput($_SESSION['user']['user_id']),
                Output::phpOutput($_POST['FirstName']),
                Output::phpOutput($_POST['LastName']),
                Output::phpOutput($_POST['Email']),
                Output::phpOutput( $_POST['Phone']));
            $this->updateImages('Congratulations, you created xa contact', $id);
        }
    }

    /**
     * Edit a contact
     *
     * @param $id
     */
    public function edit($id)
    {
        // Check if logged in
        $this->checkIfLoggedIn();

        // Get contact information
        $this->model('Contact');
        $contact = Contact::find(Output::phpOutput($id));

        // Token for form
        $token =$this->sessions->getToken();

        $this->view('contact/edit', [
            'token' => $token,
            'contact'   => $contact,
            'ID'    => $contact['conContactID'],
            'firstName' => $contact['conFirstName'],
            'lastName' => $contact['conLastName'],
            'phone' => $contact['conPhone'],
            'email' => $contact['conEmail']
        ]);

    }

    public function update()
    {
        //check if contact belongs to user
        $this->model('Contact');
        // Security check make sure record belongs to user
        $belongsTo = Contact::checkContactUser($_SESSION['user']['user_id'], Output::phpOutput($_POST['ID']));
        $this->checkSecurityValue($belongsTo, 'contacts/index', 'Something went wrong, please try again');

        // Check if logged in
        $this->checkIfLoggedIn();
        // Check if tokens match
        $this->checkTokensMatched($this->sessions->withdrawl('token'), Output::phpOutput($_POST['token']));

        // get all the posted values
        $fields = $this->getFields();

        // validate the values
        $errors = $this->validation($fields);

        if(!empty($errors))
        {
            $this->ValidationFailed('contact/create', $errors);
        } else {
            Contact::edit(Output::phpOutput($_POST['ID']), Output::phpOutput($_POST['FirstName']),
            Output::phpOutput($_POST['LastName']), Output::phpOutput($_POST['Phone']),
            Output::phpOutput($_POST['Email']));
            $this->setMessageCookie('success', 'You updated a contact');
            $this->redirectTo('contacts/photos/' . Output::phpOutput($_POST['ID']));
        }

    }


    /**
     * Processes a delete request
     */
    public function destroy()
    {
        //check if contact belongs to user
        $this->model('Contact');
        $belongsTo = Contact::checkContactUser($_SESSION['user']['user_id'], Output::phpOutput($_POST['ID']));
        $this->checkSecurityValue($belongsTo, 'contacts/index', 'Something went wrong, please try again');

        // check if logged in
        $this->checkIfLoggedIn();
        // Check if tokens match
        $this->checkTokensMatched($this->sessions->withdrawl('token'), Output::phpOutput($_POST['token']));

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
        $this->setMessageCookie('success', 'You deleted a contact');
        $this->redirectTo('contacts/index');
    }

    /**
     * Users select what image is the main image
     */
    public function selectImage()
    {
        // check if logged in
        $this->checkIfLoggedIn();

        // Get id
        $id = $this->sessions->withdrawl('contact_id');

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

    /**
     * Adds images to the database 
     */
    public function imageStore()
    {
        $this->checkIfLoggedIn();
        // Check if tokens match
        $this->checkTokensMatched($this->sessions->withdrawl('token'), Output::phpOutput($_POST['token']));

        if(!is_null($_POST['radio']))
        {
            $this->model('Image');
            Image::AddMain(Output::phpOutput($_POST['ID']), Output::phpOutput($_POST['radio']));

            $this->setMessageCookie('success', 'You have selected your main image');
            $this->redirectTo('contacts/index');

        } else {
            $this->model('Image');
            $images = Image::All(Output::phpOutput($_POST['ID']));
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

    /**
     * Users can add more photos
     */
    public function photos($id)
    {
        // Check if logged in
        $this->checkIfLoggedIn();
        // Create a token
        $token = $this->sessions->getToken();

        $this->view('contact/addPhoto' ,[
            'token' => $token,
            'id'    => Output::phpOutput($id)
        ]);
    }

    /**
     * Add photos
     */
    public function addPhotos()
    {
        // Check if logged in
        $this->checkIfLoggedIn();
        // Check if the user belongs to the photos
        $this->model('Contact');
        $contactID = Output::phpOutput($_POST['id']);
        $belongsTo = Contact::checkContactUser($_SESSION['user']['user_id'], $contactID);
        $this->checkSecurityValue($belongsTo, 'contacts/index', 'Something went wrong, please try again');

        // Check if tokens match
        $this->checkTokensMatched($this->sessions->withdrawl('token'), Output::phpOutput($_POST['token']));

        // Include the model
        $this->model('Image');

        // Variables needed
        $max = 500 * 1024; //size of the image
        $destination =  'images/contacts';

        // The name of the file or files uploaded
        $this->uploadFile($destination, $max);


        if(!empty($this->uploadErrorMessages))
        {
            $this->sessions->put('errors', $this->uploadErrorMessages);
            $this->sessions->put('test', 'test test test');
            $this->redirectTo('contacts/photos/' . $contactID);

        } else {
            $this->sessions->put('contact_id', $contactID);
            $this->updateImages('Congratulations, you added photos', $contactID);
        }
    }



    // Images Functions
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
    private function updateImages($message, $id)
    {
        foreach($this->files as $file)
        {
            // Add contact ID and file name
            Image::Add($id, $file);
            // resize the files
            $resize = new \App\library\ResizeImage($file, 400, 400);
            $resize->createResizeImage();
            $resize->createThumbNail(200, 200);
        }

        $this->setMessageCookie('success', $message);
        $this->returnSelectImagePage($id);
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
        $this->redirectTo('contacts/selectImage');
    }

    // Security Functions

    /**
     * if the site detects suspicious activity
     */
    private function stopSecurityViolation()
    {
        $this->setMessageCookie('error', 'Something went wrong please try again!');
        $this->redirectTo('contacts/index');
    }

    /**
     * Check if tokens match.  If they don't return to the index form
     *
     * @param $token
     * @param $sessionToken
     */
    private function checkTokensMatched($token, $sessionToken)
    {
        if($token !== $sessionToken)
        {
            $this->setMessageCookie('error', 'Something went wrong please try again!');
            $this->redirectTo('contacts/index');
        }
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
            'firstName' => Output::phpOutput($_POST['FirstName']),
            'lastName' => Output::phpOutput($_POST['LastName']) ,
            'phone' => Output::phpOutput($_POST['Phone']),
            'email' => Output::phpOutput($_POST['Email'])
        ]);
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
}