<?php

class HUESPED
{
    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    public function getAll()
    {

        $res = [];

        $strSQLAllHuesped = " SELECT id, nombre FROM dyalogo_general.huespedes WHERE nombre NOT LIKE '%zzz%'  ORDER BY id DESC ";
        $resSQLAllHuesped = self::$db->query($strSQLAllHuesped);
        if ($resSQLAllHuesped) {
            if ($resSQLAllHuesped->num_rows > 0) {
                while ($objHuesped = $resSQLAllHuesped->fetch_object()) {
                    array_push($res, $objHuesped);
                }
            }
        }

        return (count($res) > 0) ? $res : false;
    }
}
