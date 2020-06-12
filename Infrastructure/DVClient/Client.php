<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Infrastructure\DVClient;


class Client
{

    public function wsClient() : callable
    {
        $callable = function&( $responseResultFromWebService , $fullUrl , $fullpayload , $options=[], $method='POST')  {
            ##

            try{
                ##
                $api_web_service_request = function () use ($responseResultFromWebService , $fullpayload , $fullUrl , $method) {
                    ##
                    $client = new \GuzzleHttp\Client();
                    $responseResultFromWebService = $client->request($method , $fullUrl , $fullpayload);
                    #
                    return $responseResultFromWebService ;
                };

                ##
                if (isset($options['handler'])) {
                    ##
                    \GuzzleHttp\DefaultHandler::setDefaultHandler($options['handler']);
                }

                ##
                return call_user_func($api_web_service_request);

            }
            catch (\GuzzleHttp\Exception\ClientException $exception) {
                ##
                $response = $exception->getResponse();

                #$responseBody = $response->getBody() ;
            }
            catch (\GuzzleHttp\Exception\ServerException $exception) {
                ##
                $response = $exception->getResponse();
                #$responseBody = $response->getBody() ;
            }
            catch (\GuzzleHttp\Exception\RequestException $exception) {
                /*
                 * If there are network errors, we need to ensure the application doesn't crash.
                 * if $e->hasResponse is not null we can attempt to get the message
                 * Otherwise, we'll just pass a network unavailable message.
                 */
                if ($exception->hasResponse()) {
                    ##
                    $response = $exception->getResponse();
                }
                else {
                    $response = new \GuzzleHttp\Psr7\Response( 503 , ['content-type' => 'application/json+error'] , $exception->getMessage());
                }
                ##
                #$responseBody = $response->getBody() ;
            }

            ##
            $responseResultFromWebService = $response ;

            ##
            return $response ;
        };
        ##
        return $callable ;
    }
}