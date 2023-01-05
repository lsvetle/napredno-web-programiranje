<?php
class Notification {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    public function getAllNotifications($sorted ="") {

        if ((!$sorted) || $sorted == "DESC") {
            $this->db->query('SELECT u.username, n.notification_ID, n.message, n.user_ID, n.date, n.title, n.created_at FROM notification n INNER JOIN user u ON n.user_ID = u.user_ID ORDER BY n.created_at DESC');
        }
        if ($sorted == "ASC")  {
            $this->db->query('SELECT u.username, n.notification_ID, n.message, n.user_ID, n.date, n.title, n.created_at FROM notification n INNER JOIN user u ON n.user_ID = u.user_ID ORDER BY n.created_at ASC');
        }
        $notifications = $this->db->resultSet(); //creating array of data from table
        return $notifications;
    }

    public function findNotificationsByDate($date="") {

        $this->db->query('SELECT u.username, n.notification_ID, n.message, n.user_ID, n.date, n.title, n.created_at FROM notification n INNER JOIN user u ON n.user_ID = u.user_ID WHERE n.date = :date ORDER BY n.created_at DESC');

        $this->db->bind(':date', $date); 

        $notifications = $this->db->resultSet(); //creating array of data from table
        return $notifications;
    }

    public function addNotification ($data) {
        $this->db->query('INSERT INTO notification (user_ID, title, message, date, created_at) VALUES (:user_ID, :title, :message, :date, :created_at)');

        //adding values PDO
        $this->db->bind(':user_ID', $data['user_ID']); 
        $this->db->bind(':title', $data['title']); 
        $this->db->bind(':message', $data['message']); 
        $this->db->bind(':date', $data['date']); 
        $this->db->bind(':created_at', $data['created_at']); 

        if ($this->db->execute()){
            return true;}
        else {
            return false;
        }
    }

    public function findNotificationById($id) {

        $this->db->query('SELECT u.username, n.notification_ID, n.message, n.user_ID, n.date, n.title, n.created_at FROM notification n INNER JOIN user u ON n.user_ID = u.user_ID WHERE notification_ID = :id ORDER BY n.created_at DESC');

        $this->db->bind(':id', $id); 

        $row = $this->db->single();
        return $row;
    }

    public function findNotificationsByUser($id="") {
        $this->db->query('SELECT u.username, n.notification_ID, n.message, n.user_ID, n.date, n.title, n.created_at FROM notification n INNER JOIN user u ON n.user_ID = u.user_ID WHERE n.user_ID = :id ORDER BY n.created_at DESC');

        $this->db->bind(':id', $id); 

        $notifications = $this->db->resultSet(); //creating array of data from table
        return $notifications;
    }

    public function updateNotification($data) {
        $this->db->query('UPDATE notification SET title = :title, message = :message, date = :date, created_at = :created_at WHERE notification_ID = :notification_ID');

        //adding values PDO
        $this->db->bind(':notification_ID', $data['notification_ID']); 
        $this->db->bind(':title', $data['title']); 
        $this->db->bind(':message', $data['message']); 
        $this->db->bind(':date', $data['date']); 
        $this->db->bind(':created_at', $data['created_at']); 

        if ($this->db->execute()){
            return true;}
        else {
            return false;
        }
    }

    public function deleteNotification($id) {

        $this->db->query('DELETE FROM notification WHERE notification_ID = :id');

        // $this->db->bind(':notification_ID', $id);
        $this->db->bind(':id', $id);

        if ($this->db->execute()){
            return true;}
        else {
            return false;
        }
    }

}