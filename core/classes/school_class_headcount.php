<?php

class school_class_headcount
{
    private $year;
    private $letter;
    private $headcount;
    public function __construct(
        $year,
        $letter,
        $headcount ){
        $this->year = $year;
        $this->letter = $letter;
        $this->headcount = $headcount;
    }
    public function getYear(){
        return $this->year;}

    public function getLetter(){
        return $this->letter;}

    public function getHeadcount(){
        return $this->headcount;}
}