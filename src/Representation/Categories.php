<?php

namespace App\Representation;

use App\Entity\Category;
use JMS\Serializer\Annotation\Type;

class Categories extends AbstractRepresentation
{
    /**
     * @var iterable<Category>
     * @Type("array<App\Entity\Category>")
     */
    public $data;
}
