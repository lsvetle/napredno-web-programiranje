<?php
class Notifications extends Controller {

    private string  $sorted = "";
    private string $date ="";
    private string $id ="";
    private $dateNow ="";

    public function __construct () {
        $this->notificationModel = $this->model('Notification'); //file name of model
        $this->sorted = $_GET['sorted'] ?? ""; //ASC DESC or ""
        $this->date = $_GET['date'] ?? ""; //getting date or ""
        $this->id = $_GET['id'] ?? ""; //getting date or ""
        $this->dateNow = date("Y-m-d");
    }

    //oppening calendar page (READ)
    public function calendar() {

        if (!$this->date && !$this->id ){
                $notifications = $this->notificationModel->getAllNotifications($this->sorted);
        }else if ($this->date) {
            $notifications = $this->notificationModel->findNotificationsByDate($this->date);
        }else if ($this->id) {
            $notifications = $this->notificationModel->findNotificationsByUser($this->id);
        }else if(!isLoggedIn()){ //if user try to add notification by root
            header("Location: " . URLROOT . "/notifications/calendar");
        }

        $data = [
            'notifications' =>$notifications,
        ];

        $this->view('notifications/calendar', $data);//defining root
            //notifications iz root-a mora biti isti kao ime kalse 
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
