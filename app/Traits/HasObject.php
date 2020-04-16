<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Traits;

use ReflectionClass;
use ReflectionException;

trait HasObject
{

    /**
     * @return string|null
     */
    protected function getShortClassname(): ?string
    {
        try {
            return (new ReflectionClass($this))->getShortName();
        } catch (ReflectionException $exception) {
            $classname = get_class($this);
            if ($pos = strrpos($classname, '\\')) {
                return substr($classname, $pos + 1);
            }
            return $classname;
        }
    }
}
