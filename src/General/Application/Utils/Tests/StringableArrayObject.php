<?php

declare(strict_types=1);

namespace App\General\Application\Utils\Tests;

use App\General\Domain\Utils\JSON;
use ArrayObject;
use JsonException;
use Stringable;

/**
 * Class StringableArrayObject
 *
 * @package App\General
 */
class StringableArrayObject extends ArrayObject implements Stringable
{
    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        $iterator = static fn (mixed $input): mixed => $input instanceof Stringable ? (string)$input : $input;

        return JSON::encode(array_map($iterator, $this->getArrayCopy()));
    }
}
