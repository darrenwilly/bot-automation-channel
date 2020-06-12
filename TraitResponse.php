<?php
declare(strict_types=1);

namespace Veiw\BotLogic;

use Symfony\Component\HttpFoundation\Response;

trait TraitResponse
{
    protected $response;

    /**
     * Returns the response object.
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets a response and stops event propagation.
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        $this->stopPropagation();
    }

    /**
     * Returns whether a response was set.
     *
     * @return bool Whether a response was set
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }

}