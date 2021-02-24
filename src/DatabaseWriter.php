<?php


final class DatabaseWriter
{

    const HOSTNAME = "127.0.0.1";
    const USERNAME = "root";
    const PASSWORD = "Password";
    const DB = "wrenTest";

    /**
     * @param $rows
     */
    public static function writeToDatabase($rows) {
        $mysqli = new mysqli(self::HOSTNAME,self::USERNAME, self::PASSWORD,self::DB);

        // Check connection
        if ($mysqli -> connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
            exit();
        }

        $query = $mysqli->prepare("INSERT INTO tblProductData (strProductCode, strProductName, strProductDesc, intStock, dblCost, dtmDiscontinued, dtmAdded) VALUES (?, ?, ?, ?, ?, ?, ?)");

        foreach ($rows as $row) {
            $query->bind_param("sssidss", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
            $query->execute();
        }

        $mysqli->close();
    }
}