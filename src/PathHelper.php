<?php

namespace Famoser\Mamapp;

class PathHelper
{
    public const string ROOT_DIR = __DIR__ . '/..';
    public const string VAR_DIR = self::ROOT_DIR . '/var';
    public const string VAR_TRANSIENT_DIR = self::VAR_DIR . '/transient';
    public const string VAR_PERSISTENT_DIR = self::VAR_DIR . '/persistent';
    public const string PUBLIC_DIR = self::ROOT_DIR . '/public';
}
