<?php

namespace Famoser\Mamapp;

class PathHelper
{
    public const string VAR_DIR = __DIR__ . '/../var';
    public const string VAR_TRANSIENT_DIR = self::VAR_DIR . '/transient';
    public const string VAR_PERSISTENT_DIR = self::VAR_DIR . '/persistent';
    public const string CONFIG_DIR = self::VAR_DIR . '/config';
}
