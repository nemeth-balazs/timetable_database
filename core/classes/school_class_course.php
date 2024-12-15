<?php

class school_Class_course
{
    private $year;
    private $letter;
    private $room_number;
    private $day;
    private $start;
    private $subject_name;
    public function __construct(
        $year,
        $letter,
        $room_number,
        $day,
        $start,
        $subject_name ){
        $this->year = $year;
        $this->letter = $letter;
        $this->room_number = $room_number;
        $this->day = $day;
        $this->start = $start;
        $this->subject_name = $subject_name;
    }
    public function getYear(){
        return $this->year;}

    public function getLetter(){
        return $this->letter;}

    public function getRoomNumber(){
        return $this->room_number;}

    public function getDay(){
        return $this->day;}

    public function getStart(){
        return $this->start;}

    public function getSubjectName(){
        return $this->subject_name;}
}