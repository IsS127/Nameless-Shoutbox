<?php
/*
=   Shoutbox | NamelessMC
=   Author: Hyfalls | IsS127.com

=   Chat.php
=   Description: Chat Class
=   Created: 2019-04-14 - 00:39 - CEST
*/

class Chat
{
    private $_db;

    // Construct User class
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function deleteMessage($message_id = NULL) {
        $this->_db->update('shoutbox_messages', $message_id, array(
            'deleted' => 1
        ));
    }

    public function sendMessage($user, $message){
        $queries = new Queries();

        $queries->create('shoutbox_messages', array(
            'message' => Output::getClean($message),
            'user' => $user,
            'message_date' => date('Y-m-d H:i:s'),
            'timestamp' => date('U'),
        ));
    }

    // Params: 	$number (integer) 	- gets an x amount of messages ordered by date, limit 30 (optional)
    public function getChat($number = 20) {
        $return = array(); // Array to return containing messages

        $message_items = $this->_db->query("SELECT * FROM nl2_shoutbox_messages WHERE deleted = 0 ORDER BY message_date DESC LIMIT 30")->results();

        foreach($message_items as $item){
            $message_date = null;
            if(is_null($item[0]->timestamp)){
                $message_date = date('d M Y, H:i', strtotime($item[0]->message_date));
            } else {
                $message_date = date('d M Y, H:i', $item[0]->timestamp);
            }

            $return[] = array(
                "id" => $item->id,
                "date" => $message_date,
                "message"=> $item->message,
                "user" => $item->user,
            );
        }

        // Order the discussions by date - most recent first
        usort($return, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return array_slice($return, 0, $number, true);
    }
}