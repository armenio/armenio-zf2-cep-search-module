<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\Cep;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Json;

/**
 * @author	 Rafael Armenio
 */
class Cep implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function search($cep = '00000000')
	{
		return self::search($cep);
	}
	
	public static function search($cep = '00000000')
	{
		$result = array(
			'endereco' => '',
			'bairro' => '',
			'cep' => $cep,
			'cidade' => '',
			'estado' => '',
			'pais' => 'Brasil'
		);

		$cep = preg_replace('/[^0-9]*/', '', $cep);

		try{
			$url = sprintf('http://aircode.com.br/webservice/correios/cep/%s', $cep);
			$client = new Client($url);
			$client->setAdapter(new Curl());
			$client->setMethod('GET');
			$client->setOptions(array(
				'curloptions' => array(
					CURLOPT_HEADER => false,
				)
			));

			
			$response = $client->send();
			
				
			$body = $response->getBody();
			
			$json = Json\Json::decode($body, 1);
			//{"id":"22881","cidade":"Curitiba","logradouro":"Marechal Deodoro","bairro":"Centro","cep":"80060-010","tp_logradouro":"Rua","uf":"PR"}

			if( ! empty($json['cep']) ){
				$result = array(
					'endereco' => trim(sprintf('%s %s', $json['tp_logradouro'], $json['logradouro'])),
					'bairro' => $json['bairro'],
					'cep' => $json['cep'],
					'cidade' => $json['cidade'],
					'estado' => $json['uf'],
					'pais' => $json['pais']
				);
			}else{
				$result = $json;
			}

			$isException = false;
		} catch (\Zend\Http\Exception\RuntimeException $e){
			$isException = true;
		} catch (\Zend\Http\Client\Adapter\Exception\RuntimeException $e){
			$isException = true;
		} catch (Json\Exception\RuntimeException $e) {
			$isException = true;
		} catch (Json\Exception\RecursionException $e2) {
			$isException = true;
		} catch (Json\Exception\InvalidArgumentException $e3) {
			$isException = true;
		} catch (Json\Exception\BadMethodCallException $e4) {
			$isException = true;
		}

		if( $isException === true ){
			//código em caso de problemas no decode
		}

		return $result;
	}
}