<?php

namespace App\Helpers;

use \Exception;

class ResultSet
{
    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $msg;

    /**
     * @var mixed|null
     */
    public $data;

    /**
     * @var Exception|null
     */
    public $exception;

    /**
     * @var array
     */
    private $errcode;

    /**
     * ResultSet constructor.
     * @param $code
     * @param $msg
     * @param $data
     * @param null $exception
     */
    public function __construct($code, $msg, $data, $exception = null)
    {
        $this->errcode = config('errcode');
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
        $this->exception = $exception;

    }

    /**
     * create a ResultSet
     * @param $code
     * @param $msg
     * @param null $data
     * @param null $exception
     * @return ResultSet
     */
    public static function create($code, $msg, $data = null, $exception = null)
    {
        return new self($code, $msg, $data, $exception);

    }

    /**
     * create a success ResultSet
     * @param $data
     * @param string $msg
     * @return ResultSet
     */
    public static function success($data, $msg = "Successful")
    {
        return new self(1, $msg, $data);
    }

    /**
     * create a failure ResultSet
     * @param int $code
     * @param string $msg
     * @param null $exception
     * @param null $data
     * @return ResultSet
     */
    public static function failure($code = 0, $msg = "Failure", $exception = null, $data = null)
    {
        $msg=$msg?:"Failure";
        $errcode = config('errcode');
        if ($msg == "Failure" && isset($errcode[$code])) {
            $msg = $errcode[$code];
        }
        if ($exception && config('app.debug')) {
            $msg.="  [Exception:{$exception->getMessage()}]";
        }
        return new self($code, $msg, $data, $exception);
    }


    /**
     * create a json response
     * @return \Illuminate\Http\JsonResponse
     */
    public function response()
    {
        return response()->json([
            'code' => $this->code,
            'msg'  => $this->msg,
            'data' => $this->data,
        ]);
    }
}
