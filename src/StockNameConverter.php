<?php


namespace App;


use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

use function array_search;

final class StockNameConverter implements NameConverterInterface
{

    private static $lookup = [
        "Product Name" => "productName",
        "Product Code" => "productCode",
        "Product Description" => "productDescription",
        "Stock" => "stock",
        "Cost in GBP" => "cost",
        "Discontinued" => "discontinued",
    ];

    public function normalize(string $propertyName)
    {
        $result = array_search($propertyName, self::$lookup);

        echo($propertyName);
        if (!$result) {
            return $propertyName;
        }

        return $result;
    }

    public function denormalize(string $propertyName)
    {
        return self::$lookup[$propertyName] ?? $propertyName;
    }
}