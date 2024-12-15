<?php

include_once __DIR__."/../database/database_classroom.php";
include_once __DIR__."/../database/database_room_equipment.php";
include_once __DIR__."/../database/database_connection.php";
include_once __DIR__."/../classes/classroom.php";
include_once __DIR__."/service_common.php";

Class service_classroom
{
    public static function classroom_validate($classroom): bool
    {
        $is_valid = service_classroom::classroom_validate_capacity($classroom);
        if(!$is_valid){
            $_SESSION["error"] = "Hibás férőhely!";
            return false;
        }

        return true;
    }

    public static function classroom_validate_capacity($classroom): bool
    {
        if (!$classroom->getClassroomCapacity()) return true;

        if ( 1 > $classroom->getClassroomCapacity() || $classroom->getClassroomCapacity() > 40)
            return false;

        return true;
    }

    public static function classroom_add_to_database($classroom): ?bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_classroom::add_to_database($database->getConnection(),
                $classroom->getClassroomNumber(), $classroom->getClassroomCapacity());

            if(!$is_valid){
                $_SESSION["error"] = "Osztályterem hozzáadás nem sikerült az adatbázishoz!";
                return $is_valid;
            }

            if (!$classroom->getClassroomEquipment()) return true;

            $equipment_array = service_common::prepare_array_items($classroom->getClassroomEquipment());
            $is_valid = database_room_equipment::add_to_database($database->getConnection(), $classroom->getClassroomNumber(), $equipment_array);
            if(!$is_valid){
                $_SESSION["error"] = "Osztályterem hozzáadás nem sikerült az adatbázishoz!";
                return $is_valid;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztályterem hozzáadás nem sikerült az adatbázishoz!";
            return false;}
    }

    public static function classroom_delete_from_database($classroom): bool
    {
        try{

            $database = new database_connection();
            database_classroom::delete_from_database($database->getConnection(), $classroom->getClassroomNumber());
            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztályterem törlése nem sikerült az adatbázisból!";
            return false;}
    }

    public static function classroom_modify_in_database($classroom): ?bool
    {
        try{
            $database = new database_connection();

            $is_valid = database_classroom::modify_in_database($database->getConnection(),
                $classroom->getClassroomNumber(), $classroom->getClassroomCapacity());

            if(!$is_valid){
                $_SESSION["error"] = "Osztályterem módosítása nem sikerült az adatbázisban!";
                return $is_valid;
            }

            if (!$classroom->getClassroomEquipment()) return true;

            $equipment_array = service_common::prepare_array_items($classroom->getClassroomEquipment());
            $is_valid = database_room_equipment::modify_in_database($database->getConnection(), $classroom->getClassroomNumber(), $equipment_array);
            if(!$is_valid){
                $_SESSION["error"] = "Osztályterem módosítása nem sikerült az adatbázisban!";
                return $is_valid;
            }

            return true;
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Osztályterem módosítása nem sikerült az adatbázisban!";
            return false;}
    }

    public static function classroom_get_all_classroom_with_equipments(): ?array
    {
        try{
            $database = new database_connection();
            return database_classroom::get_all_classroom_with_equipments($database->getConnection());
        }
        catch (mysqli_sql_exception $e) {
            $_SESSION["error"] = "Hibás adatbázis lekérdezés!";
            return null;}
    }
}

