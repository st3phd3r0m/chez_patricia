<?php

namespace App\Representation;

use JMS\Serializer\Annotation\Type;

class NotFoundObject
{

    public int $code_status;

    public string $message;

    public function __construct(int $code_status, string $message)
    {
        $this->code_status = $code_status;
        $this->message = $message;
    }
    
}
