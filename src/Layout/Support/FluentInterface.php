<?php
/** {license_text}  */
namespace Layout\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface FluentInterface
    extends \ArrayAccess, Arrayable, Jsonable, \JsonSerializable
{
    
}
