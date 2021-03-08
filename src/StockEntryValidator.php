<?php


namespace App;

use function is_numeric;


final class StockEntryValidator
{
    /**
     * @var StockEntry[]
     */
    private array $lines = [];

    /**
     * @var StockEntry[]
     */
    private array $invalidLines = [];

    private const MINVALUE = 5;
    private const MAXVALUE = 1000;
    private const MINQUANTITY = 100;

    /**
     * @param StockEntry[] $csvLines
     */
    public function validate(array $csvLines): void
    {
        foreach ($csvLines as $line) {
            if(
                !isset($line->cost) ||
                !isset($line->discontinued) ||
                !isset($line->stock) ||
                !isset($line->productCode) ||
                !isset($line->productDescription) ||
                !isset($line->productName) ||
                !is_numeric($line->cost)
            ) {
                $this->invalidLines[] = $line;
                continue;
            }

            $quantity = (int) $line->stock;
            $cost = (int) $line->cost;

            if ($cost < self::MINVALUE && $quantity < self::MINQUANTITY || $cost > self::MAXVALUE) {
                $this->invalidLines[] = $line;
                continue;
            }

            $this->lines[] = $line;
        }
    }

    /**
     * @return StockEntry[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @return StockEntry[]
     */
    public function getInvalidLines(): array
    {
        return $this->invalidLines;
    }
}