<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\Cep;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * CepServiceFactory
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class CepServiceFactory implements FactoryInterface
{
    /**
     * zend-servicemanager v2 factory for creating Cep instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns Cep
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cep = new Cep();
        return $cep;
    }
}
