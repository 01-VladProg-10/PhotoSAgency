<?php

class Session{

public $user_id;
public $session_id;

public function __construct($data = []){
    $this->user_id = $data['user_id'];
    $this->session_id = $data['session_id'] ?? null;
}

}

?>