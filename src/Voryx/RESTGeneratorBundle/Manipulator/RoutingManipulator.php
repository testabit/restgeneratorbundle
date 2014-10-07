<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voryx\RESTGeneratorBundle\Manipulator;

use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;

/**
 * Changes the PHP code of a YAML routing file.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RoutingManipulator extends Manipulator
{
    private $file;

    /**
     * Constructor.
     *
     * @param string $file The YAML routing file path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Adds a routing resource at the top of the existing ones.
     *
     * @param string $bundle
     * @param string $entity
     * @param string $prefix
     *
     * @return Boolean true if it worked, false otherwise
     *
     * @throws \RuntimeException If bundle is already imported
     */
    public function addResource($bundle, $entity, $prefix)
    {
        $route = Container::underscore(substr($bundle, 0, -6)).('/' !== $prefix ? '_'.str_replace('/', '_', substr($prefix, 1)) : '');

        $current = '';
        if (file_exists($this->file)) {
            $current = file_get_contents($this->file);

            // Don't add same route twice
            if (false !== strpos($current, $route)) {
                return false;
            }
        } elseif (!is_dir($dir = dirname($this->file))) {
            mkdir($dir, 0777, true);
        }

        $code = sprintf("%s:\n", $route);
        $code .= sprintf("    resource: \"@%s/Controller/%sRESTController.php\"\n    type:     rest\n", $bundle, $entity);
        $code .= sprintf("    prefix:   %s\n", $prefix);
        $code .= "\n";
        $code .= $current;

        if (false === file_put_contents($this->file, $code)) {
            return false;
        }

        return true;
    }
}
