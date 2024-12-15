<?php

class teacher_course
{
    private $user_name;
    private $room_number;
    private $day;
    private $start;
    private $subject_name;
    public function __construct(
        $user_name,
        $room_number,
        $day,
        $start,
        $subject_name ){
        $this->user_name = $user_name;
        $this->room_number = $room_number;
        $this->day = $day;
        $this->start = $start;
        $this->subject_name = $subject_name;
    }
    public function getUserName(){
        return $this->user_name;}

    public function getRoomNumber(){
        return $this->room_number;}

    public function getDay(){
        return $this->day;}

    public function getStart(){
        return $this->start;}

    public function getSubjectName(){
        return $this->subject_name;}
}