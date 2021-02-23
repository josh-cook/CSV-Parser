<?php

final class CsvReader
{
    public static function fromFile($filename): Array
    {
        $csv = file($filename);
        $lines = [];

        foreach ($csv as $line) {
            $lines[] = str_getcsv($line);
        }

        return $lines;
    }

    public static function ApplyRules($csvLines): Array
    {
        // if quantity < 100 & cost < 5 remove line
        // if cost > 1000 remove line
        // if discontinued mark discontinued date as todays date

        $lines = [];
        $invalidLines = [];

        foreach ($csvLines as $line) {
            $quantity = (int) $line[3];
            $cost = (int) $line[4];

            if ($cost < 5 && $quantity < 10 || $cost > 1000) {
                $invalidLines[] = $line;
                continue;
            }

            $lines[] = $line;
        }

        // return both arrays as we need to report any removed lines.
        return [
            "lines" => $lines,
            "invalid" => $invalidLines,
        ];
    }
}