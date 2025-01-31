<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The main application kernel.
 * This class serves as the entry point for the Symfony application,
 * extending the base functionality with the MicroKernelTrait.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait; // Enables a minimalistic configuration for the application kernel.
}
