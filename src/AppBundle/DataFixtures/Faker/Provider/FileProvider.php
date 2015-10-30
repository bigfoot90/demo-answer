<?php

/**
 * @author Damian Dlugosz <bigfootdd@gmail.com>
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
