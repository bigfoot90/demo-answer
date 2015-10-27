<?php

/**
 * @author Damian Dlugosz <d.dlugosz@bestnetwork.it>
 */

namespace AppBundle\DataFixtures\Faker\Provider;

use Symfony\Component\HttpFoundation\File\File;

class FileProvider
{
    public static function File($path)
    {
        return new File(__DIR__.'/../'.$path);
    }
}
