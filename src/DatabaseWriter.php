<?php

namespace App;

use DateTime;
use mysqli;
use function strtolower;

final class DatabaseWriter
{

    const HOSTNAME = "192.168.1.77";
    const USERNAME = "root";
    const PASSWORD = "Password";
    const DB = "wrenTest";

    /**
     * @param StockEntry[] $rows
     */
    public static function writeToDatabase(array $rows) {
        $mysqli = new mysqli(self::HOSTNAME,self::USERNAME, self::PASSWORD,self::DB);

        // Check connection
        if ($mysqli -> connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
            exit();
        }

        $query = $mysqli->prepare("INSERT INTO tblProductData (strProductCode, strProductName, strProductDesc, intStock, dblCost, dtmDiscontinued, dtmAdded) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $mysqli->begin_transaction();

        /**
         * Use current date time if discontinued
         */
        foreach ($rows as $row) {
            $today = (new DateTime())->format(DateTime::ATOM);
            $discontinued = strtolower($row->discontinued) === "yes" ? $today : NULL;
            $query->bind_param("sssidss", $row->productCode, $row->productName, $row->productDescription, $row->stock, $row->cost, $discontinued, $today);
            $query->execute();
        }

        $mysqli->commit();
        $mysqli->close();
    }
}