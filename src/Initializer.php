<?php

namespace Famoser\Mamapp;

use Psr\Http\Message\StreamInterface;

class Initializer
{
    public static function init(StreamInterface $output): void
    {
        // read out xlsx, transform to JSON
        // find all images, resize, copy to /public/data/images
        // copy mammals.json to /public/data
    }
}
