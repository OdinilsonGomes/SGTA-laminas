<?php

declare(strict_types=1);

namespace DoctrineORMModule\Service;

use DoctrineORMModule\CliConfigurator;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

final class CliConfiguratorFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, ?array $options = null)
    {
        return new CliConfigurator($serviceLocator);
    }
}
