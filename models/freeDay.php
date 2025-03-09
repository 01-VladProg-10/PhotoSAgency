<?php

class FreeDay{

public $date;
public $is_booked;

public function __construct($data = []){
    $this->date = $data['date'];
    $this->is_booked = $data['is_booked'] ?? null;
}

}

?>