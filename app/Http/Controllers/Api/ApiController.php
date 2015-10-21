<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Core\Facades\Fractal;
use League\Fractal\Resource\Item;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Collection;

class ApiController extends Controller
{
    const CODE_WRONG_ARGS = 'GEN-FUBARGS';
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
    const CODE_INTERNAL_ERROR = 'GEN-AAAGHH';
    const CODE_UNAUTHORIZED = 'GEN-MAYBGTFO';
    const CODE_FORBIDDEN = 'GEN-GTFP';

    protected $statusCode = 200;

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $newCode
     * @return $this
     */
    protected function setStatusCode($newCode)
    {
        $this->statusCode = $newCode;

        return $this;
    }

    /**
     * @param $item
     * @param $callback
     * @return mixed
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $data = Fractal::createData($resource);

        return $this->respondWithArray($data->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     * @return mixed
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);

        $data = Fractal::createData($resource);

        return $this->respondWithArray($data->toArray());
    }

    /**
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    protected function respondWithArray(array $data, array $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    /**
     * @param $message
     * @param $errorCode
     * @return mixed
     */
    public function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error("Are you reporting error on a success request?", E_USER_WARNING);
        }

        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, static::CODE_FORBIDDEN);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, static::CODE_INTERNAL_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function errorNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, static::CODE_NOT_FOUND);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, static::CODE_UNAUTHORIZED);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }
}
