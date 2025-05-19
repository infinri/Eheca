<?php

declare(strict_types=1);

namespace Eheca;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir().'/config';
        
        $loader->load($confDir.'/packages/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/services'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/*'.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes($routes): void
    {
        $confDir = $this->getProjectDir().'/config';
        
        // Import routes from the routes directory
        $routes->import($confDir.'/routes/*.yaml');
        
        // Load routes from controllers using attributes
        if (is_dir($this->getProjectDir().'/src/Controller/')) {
            $routes->import($this->getProjectDir().'/src/Controller/', 'attribute');
        }
    }
}
