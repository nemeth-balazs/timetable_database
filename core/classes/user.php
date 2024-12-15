<?php

class user
{
    private $id;
    private $name;
    private $email;
    private $phone;
    private $password_1;
    private $password_2;
    private $level;
    private $subjects;

    public function __construct(
        $id,
        $name,
        $email,
        $phone,
        $password_1,
        $password_2,
        $level,
        $subjects){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password_1 = $password_1;
        $this->password_2 = $password_2;
        $this->level = $level;
        $this->subjects = $subjects;
    }

    public function getUserId(){
        return $this->id;}

    public function setUserId($user_id){
        $this->id = $user_id;}

    public function getUserName(){
        return $this->name;}

    public function setUserName($user_name){
        $this->name = $user_name;}

    public function getUserEmail(){
        return $this->email;}

    public function setUserEmail($user_email){
        $this->email = $user_email;}

    public function getUserPhone(){
        return $this->phone;}

    public function setUserPhone($user_phone){
        $this->phone = $user_phone;}

    public function getUserPassword_1(){
        return $this->password_1;}

    public function getUserPassword_2(){
        return $this->password_2;}

    public function setUserPassword_1($user_password){
        $this->password_1 = $user_password;}

    public function setUserPassword_2($user_password){
        $this->password_2 = $user_password;}

    public function getUserLevel(){
        return $this->level;}

    public function setUserLevel($user_level){
        $this->level = $user_level;}

    public function getUserSubjects(){
        return $this->subjects;}

    public function setUserSubjects($user_subjects){
        $this->subjects = $user_subjects;}

}