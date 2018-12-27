<?php

namespace Support;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Summary of ResponseTraits
 */
trait ResponseTraits
{
    /**
     * Summary of ErrorResponse
     * @param string $content
     * @param mixed $headers
     * @return Response
     */
    protected function ErrorResponse(string $content, $headers = [])
    {
        return new Response($content, 500, $headers);
    }

    /**
     * Summary of JsonResponse
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function JsonResponse($data, int $status = 200, array $headers = [], bool $json = false)
    {
        return new JsonResponse($data, $status, $headers, $json);
    }

    /**
     * Summary of Redirect
     * @param \?string $uri
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    protected function Redirect(?string $uri = '', int $status = 302, array $headers = [])
    {
        return new RedirectResponse($uri, $status, $headers);
    }

    /**
     * Summary of Response
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function Response(string $content = '', int $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * @param string $view
     * @return mixed
     */
    protected function View(string $view): Response
    {
        if (file_exists(ViewPath . $view . ".html"))
        {
            ob_start();

            include ViewPath . $view . ".html";

            $content = ob_get_contents();

            ob_end_clean();

            return $this->Response($content);
        }
        else if (file_exists(ViewPath . $view . ".php"))
        {

            ob_start();

            include ViewPath . $view . ".php";

            $content = ob_get_contents();

            ob_end_clean();

            return $this->Response($content);
        }
        var_dump(ViewPath . $view);

        return $this->ErrorResponse('Sorry , the page you are looking for could not be found');
    }
}
