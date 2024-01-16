<?php
declare(strict_types=1);
namespace App\Common;

enum CategoryType: int
{
    case Products = 1;
    case Fruits = 2;

    static function toCategoryTypeName(self $categoryType): string
    {
        $resultName = "";
        switch ($categoryType)
        {
            case CategoryType::Products:
            {
                $resultName = "Продукты";
                break;
            }
            case CategoryType::Fruits:
            {
                $resultName = "Фрукты";
                break;
            }
        }
        return $resultName;
    }

    static function fromCategoryTypeName(string $categoryTypeName): self
    {
        switch ($categoryTypeName)
        {
            case "Продукты":
            {
                return CategoryType::Products;
            }
            case "Фрукты":
            {
                return CategoryType::Fruits;
            }
            default:
            {
                throw new \Exception("can not transform category name to enum. given $categoryTypeName");
            }
        }
    }
}

