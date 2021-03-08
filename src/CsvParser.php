<?php


namespace App;


use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class CsvParser
{
    private Serializer $serializer;

    public function __construct()
    {
        $converter = new StockNameConverter();

        $this->serializer = new Serializer([
            new ObjectNormalizer(null, $converter),
            new ArrayDenormalizer,
        ], [
            new CsvEncoder,
        ]);
    }

    /**
     * @param string $csv
     * @return StockEntry[]
     */
    public function parse(string $csv): array
    {
        return $this->serializer->deserialize($csv, StockEntry::class . "[]", "csv");
    }
}