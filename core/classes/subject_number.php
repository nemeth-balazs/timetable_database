<?php

class subject_number
{
    private $user_name;
    private $email;
    private $phone;
    private $subject_number;

    public function __construct(
        $name,
        $user_name,
        $phone,
        $subject_number){
        $this->user_name = $name;
        $this->email = $user_name;
        $this->phone = $phone;
        $this->subject_number = $subject_number;
    }
    public function getUserName(){
        return $this->user_name;}

    public function getUserEmail(){
        return $this->email;}

    public function getUserPhone(){
        return $this->phone;}

    public function getSubjectNumber(){
        return $this->subject_number;}
}