<?php

namespace App\Representation;

use App\Entity\Comment;
use JMS\Serializer\Annotation\Type;

class Comments extends AbstractRepresentation
{
    /**
     * @var iterable<Comment>
     * @Type("array<App\Entity\Comment>")
     */
    public $data;
}
