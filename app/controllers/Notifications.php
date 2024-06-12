<?php

require '../vendor/autoload.php';
use DeepL\Translator;
class Notifications extends Controller {

    private string  $sorted = "";
    private string $date ="";
    private string $id ="";
    private $dateNow ="";
    private string $language = "";

    public function __construct () {
        $this->notificationModel = $this->model('Notification'); //file name of model
        $this->sorted = $_GET['sorted'] ?? ""; //ASC DESC or ""
        $this->date = $_GET['date'] ?? ""; //getting date or ""
        $this->id = $_GET['id'] ?? ""; //getting date or ""
        $this->dateNow = date("Y-m-d");
        $this->language = $_GET['language'] ?? ""; //getting date or ""
    }

    //oppening calendar page (READ)
    public function calendar() {
        // Check if the user is logged in
        if (!isLoggedIn()) {
            error_log("User not logged in, redirecting to login page.");
            header("Location: " . URLROOT . "/users/login");
            return;
        }
    
        // Variable to store notifications
        $notifications = [];
    
        try {
            // Fetch and translate notifications based on conditions
            if ($this->language) {
                error_log("Fetching and translating notifications for language: " . $this->language);
                $notifications = $this->notificationModel->getAllNotifications($this->sorted);
                $authKey = "6ea71f12-4508-4175-a119-1c15d04b64f9:fx"; // Replace with your key
                $translator = new Translator($authKey);
    
                foreach ($notifications as &$notification) {
                    // Translate the title and description for each notification
                    $notification->title = $translator->translateText($notification->title, null, $this->language);
                    $notification->message = $translator->translateText($notification->message, null, $this->language);
                    error_log("Translated notification ID " . $notification->notification_ID . ": " . $notification->title);
                }
            } elseif ($this->date) {
                error_log("Fetching notifications for date: " . $this->date);
                $notifications = $this->notificationModel->findNotificationsByDate($this->date);
            } elseif ($this->id) {
                error_log("Fetching notifications for user ID: " . $this->id);
                $notifications = $this->notificationModel->findNotificationsByUser($this->id);
            } else {
                error_log("Fetching all notifications sorted: " . $this->sorted);
                $notifications = $this->notificationModel->getAllNotifications($this->sorted);
            }
        } catch (Exception $e) {
            error_log("Error fetching or translating notifications: " . $e->getMessage());
        }
    
        // Prepare data for view
        $data = [
            'notifications' => $notifications,
        ];
    
        // Load the view with notifications
        $this->view('notifications/calendar', $data);
        error_log("Loaded calendar view with notifications.");
    }
    
    
    public function create() {
        if(!isLoggedIn()){ //if user try to add notification by root
            header("Location: " . URLROOT . "/notifications/calendar");
        }

        $data = [
            'user_ID' => '',
            'title' => '',
            'message' => '',
            'date' => '',
            'created_at' => '',
            'titleError'=> '',
            'messageError' => '',
            'dateError' => ''
        ];

        //check if user sumbited form
        if ($_SERVER['REQUEST_METHOD'] =='POST') {

            //senetize data, second param is to string
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //trim removes unwanted spaces
            $data = [
                'user_ID' => $_SESSION['user_id'], //we know which user made notif.
                'title' => trim($_POST['title']),//uzme iz message (name)
                'message' => trim($_POST['message']), //uzme iz message (name)
                'date' => trim($_POST['date']), //uzmemo ga iz date (name)
                'created_at' => date("Y-m-d h:i:sa"),
                'titleError'=> '',
                'messageError' => '',
                'dateError' => ''
            ];

            if(empty($data['title'])){
                $data['titleError'] = "The title of notification can't be empty!";
            }

            if(empty($data['message'])){
                $data['messageError'] = "The body of notification can't be empty!";
            }

            if(($data['date'] < $this->dateNow) && (!empty($data['date']))){
                $data['dateError'] = "Deadline is in the past!";
            }

            //adding notification
            if(empty($data['titleError']) && empty($data['messageError']) && empty($data['dateError'])){
                if($this->notificationModel->addNotification($data)) {
                    //if all went good we go to calendar
                    header("Location: " . URLROOT . "/notifications/calendar");
                }else {
                    die("Something went wrong, please try again!");
                }
            }else {
                //if we have error we are staying at page
                $this->view('notifications/create', $data);
            }

        }

        $this->view('notifications/create', $data); //root to create page
    }

    public function update ($id) {

        $notification =$this->notificationModel->findNotificationById($id);

        if(!isLoggedIn() ) {
            header("Location: " . URLROOT . "/notifications/calendar");
        } else if ($notification->user_ID != $_SESSION['user_id']) {
            header("Location: " . URLROOT . "/notifications/calendar");
        }

        $data = [
            'notification_ID' => '',
            'notification' => $notification,
            'user_ID' => '',
            'title' => '',
            'message' => '',
            'date' => '',
            'created_at' => '',
            'titleError'=> '',
            'messageError' => '',
            'dateError' => ''
        ];

        //check if user sumbited form
        if ($_SERVER['REQUEST_METHOD'] =='POST') {

            //senetize data, second param is to string
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            //trim removes unwanted spaces
            $data = [
                'user_ID' => $_SESSION['user_id'], //we know which user made notif.
                'notification_ID' => $id,
                'notification' => $notification,
                'title' => trim($_POST['title']),//uzme iz message (name)
                'message' => trim($_POST['message']), //uzme iz message (name)
                'date' => trim($_POST['date']), //uzmemo ga iz date (name)
                'created_at' => date("Y-m-d h:i:sa"),
                'titleError'=> '',
                'messageError' => '',
                'dateError' => ''
            ];

            if(empty($data['title'])){
                $data['titleError'] = "The title of notification can't be empty!";
            }

            if(empty($data['message'])){
                $data['messageError'] = "The body of notification can't be empty!";
            }

            if($data['date']< $this->dateNow && (!empty($data['date']))){
                $data['dateError'] = "Deadline is in the past!";
            }

            //checking if there is some new value for title
            // if ($data['title'] ==                         $this->notificationModel->findNotificationById($id)->title) {
            //     $data['titleError']= "At least change the title.";
            // }

            // //checking if there is some new value for message
            // if ($data['message'] == $this->notificationModel->findNotificationById($id)->message) {
            //     $data['messageError']= "At least change the body.";
            // }

            // //checking if there is some new value for date
            // if ($data['date'] == $this->notificationModel->findNotificationById($id)->date) {
            //     $data['dateError']= "At least change the date.";
            // }

            //adding notification
            if(empty($data['titleError']) && empty($data['messageError']) && empty($data['dateError'])){
                if($this->notificationModel->updateNotification($data)) {
                    //if all went good we go to calendar
                    header("Location: " . URLROOT . "/notifications/calendar");
                }else {
                    die("Something went wrong, please try again!");
                }
            }else {
                //if we have error we are staying at page
                $this->view('notifications/update', $data);
            }

        }
        $this->view('notifications/update', $data);

    }

    public function delete ($id) {

        $notification =$this->notificationModel->findNotificationById($id);

        if(!isLoggedIn() ) {
            header("Location: " . URLROOT . "/notifications/calendar");
        } else if ($notification->user_ID != $_SESSION['user_id']) {
            header("Location: " . URLROOT . "/notifications/calendar");
        }

        $data = [
            'notification_ID' => '',
            'notification' => $notification,
            'user_ID' => '',
            'title' => '',
            'message' => '',
            'date' => '',
            'created_at' => '',
            'titleError'=> '',
            'messageError' => '',
            'dateError' => ''
        ];

         //check if user sumbited form
         if ($_SERVER['REQUEST_METHOD'] =='POST') {

            //senetize data, second param is to string
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if($this->notificationModel->deleteNotification($id)) {
                header("Location: " . URLROOT . "/notifications/calendar");
            }else {
                die("Something went wrong!");
            }
         }
    }
}
