<?php

namespace App\Data;

class Permission
{
    public function __construct(
        public string  $name,
        public string  $label,
        public ?string $description = null
    )
    {
    }
}
