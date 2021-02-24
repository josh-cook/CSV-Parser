<?php

final class CsvReader
{

    /**
     * @param $filename
     * @return array[]
     */
    public static function fromFile($filename): Array
    {
        $csv = file($filename);
        $lines = [];

        foreach ($csv as $line) {
            $lines[] = str_getcsv($line);
        }

        $appliedRules = self::ApplyRules(array_slice($lines, 1));
        self::ApplyDateDiscontinued($appliedRules["lines"]);

        return $appliedRules;
    }

    /**
     * @param $csvLines
     * @return array[]
     *
     * if price isn't readable delete line
     * if quantity < 100 & cost < 5 remove line
     * if cost > 1000 remove line
     */
    private static function ApplyRules($csvLines): Array
    {
        $lines = [];
        $invalidLines = [];

        foreach ($csvLines as $line) {
            $quantity = (int) $line[3];

            if (!is_numeric($line[4])) {
                $invalidLines[] = $line;
                continue;
            }

            $cost = (int) $line[4];

            if ($cost < 5 && $quantity < 10 || $cost > 1000) {
                $invalidLines[] = $line;
                continue;
            }

            $lines[] = $line;
        }

        return [
            "lines" => $lines,
            "invalid" => $invalidLines,
        ];
    }

    /**
     * @param $lines
     *
     * if discontinued mark discontinued date as today's date
     */
    private static function ApplyDateDiscontinued(&$lines) {
        foreach ($lines as &$line) {
            $discontinued = strtolower($line[5]);

            $line[5] = $discontinued === "yes" ? (new DateTime())->format(DateTime::ATOM) : NULL;
        }
    }
}