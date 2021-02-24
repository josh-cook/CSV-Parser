<?php

require_once "CsvReader.php";
require_once "DatabaseWriter.php";

if ($argc < 2) {
    echo "Error: No file submitted \n";
    exit;
}

$file = CsvReader::fromFile($argv[1]);

$invalidLines = $file["invalid"];
$validLines = $file["lines"];

echo "These records are invalid\n";
foreach ($invalidLines as $line) {
    echo implode(',', $line) . "\n";
}

if ($argc > 2 && $argv[2] === "test") {
    echo "These records are valid\n";
    foreach ($validLines as $line) {
        echo implode(',', $line) . "\n";
    }
} else {
    DatabaseWriter::writeToDatabase($validLines);
}