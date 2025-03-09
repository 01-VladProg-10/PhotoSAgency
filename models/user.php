<?php
class UserModel {
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $specialization;
    public $profilePhoto;
    public $coverPhoto;
    
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->specialization = $data['specialization'] ?? '';
        $this->profilePhoto = $data['profilePhoto'] ?? null;
        $this->coverPhoto = $data['coverPhoto'] ?? null;
    }
}
