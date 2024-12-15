<?php

class course
{
    private $room_number;
    private $day;
    private $start;
    private $subject_name;
    private $teacher_email_array;
    private $class_array;

    public function __construct(
        $room_number,
        $day,
        $start,
        $subject_name,
        $teacher_email_array,
        $class_array){
        $this->room_number = $room_number;
        $this->day = $day;
        $this->start = $start;
        $this->subject_name = $subject_name;
        $this->teacher_email_array = $teacher_email_array;
        $this->class_array = $class_array;
    }

    public function getRoomNumber(){
        return $this->room_number;}

    public function setRoomNumber($room_number){
        $this->room_number = $room_number;}

    public function getDay(){
        return $this->day;}

    public function setDay($day){
        $this->day = $day;}

    public function getStart(){
        return $this->start;}

    public function setStart($start){
        $this->start = $start;}

    public function getSubjectName(){
        return $this->subject_name;}

    public function setSubjectName($subject_name){
        $this->subject_name = $subject_name;}

    public function getTeacherEmail_array(){
        return $this->teacher_email_array;}

    public function &getTeacherEmail_array_to_modify(){
        return $this->teacher_email_array;}

    public function addTeacherEmail($teacher_email){
        $this->teacher_email_array[] = $teacher_email;}

    public function getClass_array(){
        return $this->class_array;}

    public function addClass($class_year_and_letter){
        $this->class_array [] = $class_year_and_letter;}
}