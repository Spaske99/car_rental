<?php

namespace App\DTO;

use Doctrine\DBAL\Types\BlobType;

class CarDTO 
{
    /**
     * @param int id
     * @param string brand
     * @param string model
     * @param string dailyPrice
     * @param string description
    //  * @param BlobType image
     */

    public function __construct(
        public readonly int    $id,
        public readonly string $brand,
        public readonly string $model,
        public readonly string $dailyPrice,
        public readonly string $description,
        // public readonly BlobType $image
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            "id"            => $this->id,
            "brand"         => $this->brand,
            "model"         => $this->model,
            "dailyPrice"    => $this->dailyPrice,
            "description"   => $this->description,
            // "image"         => $this->image
        ];
    }
}