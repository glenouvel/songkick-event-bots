<?php

namespace App\Tests;

use Soundcharts\ShazamApiClientBundle\SoundchartsShazamApiClientBundle;
use Soundcharts\WrapperBundle\SoundchartsWrapperBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Swarrot\SwarrotBundle\SwarrotBundle(),
            new SoundchartsWrapperBundle(),
            new \Soundcharts\SwarrotProcessManagerBundle\SoundchartsSwarrotProcessManagerBundle(),
            new \Snc\RedisBundle\SncRedisBundle(),
            new \Soundcharts\ApiClientBundle\SoundchartsApiClientBundle(),
            new \Soundcharts\SongkickApiClientBundle\SoundchartsSongkickApiClientBundle()
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Kernel::build()
     */
    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TestCompilerPass(), PassConfig::TYPE_OPTIMIZE);
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }
}
