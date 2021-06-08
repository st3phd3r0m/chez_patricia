<?php

namespace App\Representation;

use App\Entity\Product;
use JMS\Serializer\Annotation\Type;

class Products extends AbstractRepresentation
{
    /**
     * @var iterable<Product>
     * @Type("array<App\Entity\Product>")
     */
    public $data;
}
