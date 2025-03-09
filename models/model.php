<?php

class Model
{
    public $id;
    public $hobbies;
    public $age;
    public $height;
    public $dimensions;
    public $eye_color;
    public $hair_color;
    public $experience;
    public $phone_number;
    public $instagram;
    public $user_id;
    public $photo_main;
    public $photos = [];
    public $video;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->hobbies = $data['hobbies'] ?? '';
        $this->age = $data['age'] ?? 0;
        $this->height = $data['height'] ?? 0;
        $this->dimensions = $data['dimensions'] ?? '';
        $this->eye_color = $data['eye_color'] ?? '';
        $this->hair_color = $data['hair_color'] ?? '';
        $this->experience = $data['experience'] ?? 0;
        $this->phone_number = $data['phone_number'] ?? '';
        $this->instagram = $data['instagram'] ?? '';
        $this->user_id = $data['user_id'] ?? null;
        $this->photos = $data['photo_paths'] ?? []; // Dodane: przypisanie dodatkowych zdjęć (tablica)
        $this->video = $data['video'] ?? null;
    }
}
