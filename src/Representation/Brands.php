<?php

namespace App\Representation;

use App\Entity\Brand;
use JMS\Serializer\Annotation\Type;

class Brands extends AbstractRepresentation
{
    /**
     * @var iterable<Brand>
     * @Type("array<App\Entity\Brand>")
     */
    public $data;
}
