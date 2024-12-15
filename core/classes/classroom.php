<?php

class classroom
{
    private $room_number;
    private $capacity;
    private $equipment;

    public function __construct(
        $room_number,
        $capacity,
        $equipment){
        $this->room_number = $room_number;
        $this->capacity = $capacity;
        $this->equipment = $equipment;
    }

    public function getClassroomNumber(){
        return $this->room_number;}

    public function setClassroomNumber($classroom_number){
        $this->room_number = $classroom_number;}

    public function getClassroomCapacity(){
        return $this->capacity;}

    public function setClassroomCapacity($classroom_capacity){
        $this->capacity = $classroom_capacity;}

    public function getClassroomEquipment(){
        return $this->equipment;}

    public function setClassroomEquipment($classroom_equipment){
        $this->equipment = $classroom_equipment;}

}