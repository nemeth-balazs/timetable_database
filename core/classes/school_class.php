<?php

class school_class
{
    private $year;
    private $letter;
    private $headcount;
    private $start_year;
    private $headmaster_email;
    private $division;
    public function __construct(
        $year,
        $letter,
        $headcount,
        $start_year,
        $headmaster_email,
        $division){
        $this->year = $year;
        $this->letter = $letter;
        $this->headcount = $headcount;
        $this->start_year = $start_year;
        $this->headmaster_email = $headmaster_email;
        $this->division = $division;
    }

    public function getYear(){
        return $this->year;}

    public function setYear($year){
        $this->year = $year;}

    public function getLetter(){
        return $this->letter;}

    public function setLetter($letter){
        $this->letter = $letter;}

    public function getHeadcount(){
        return $this->headcount;}

    public function setHeadcount($headcount){
        $this->headcount = $headcount;}

    public function getStartYear(){
        return $this->start_year;}

    public function setStartYear($start_year){
        $this->start_year = $start_year;}

    public function getHeadmasterEmail(){
        return $this->headmaster_email;}

    public function setHeadmasterEmail($headmaster_email){
        $this->headmaster_email = $headmaster_email;}

    public function getDivision(){
        return $this->division;}

    public function setDivision($division){
        $this->division = $division;}

}