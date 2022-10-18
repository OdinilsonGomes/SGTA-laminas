<?php
namespace SGTA;

use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\Uri\UriFactory;

UriFactory::registerScheme('chrome-extension', 'Laminas\Uri\Uri');
class Module implements ApiToolsProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Laminas\ApiTools\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
                ],
            ],
        ];
    }
}
