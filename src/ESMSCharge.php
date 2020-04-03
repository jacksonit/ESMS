<?php

namespace Jacksonit\ESMS;

use Validator;


class ESMSCharge
{
    public $APIKEY = '';
    public $SECRETKEY = '';
    public $BRANDNAME = '';
    public $URL_API = '';

    /**
     * Create new
     *
     * @return void
     */
    public function __construct()
    {
        $this->APIKEY       = config('esms.APIKEY');
        $this->SECRETKEY    = config('esms.SECRETKEY');
        $this->BRANDNAME    = config('esms.BRANDNAME');
        $this->URL_API      = config('esms.URL_API');
    }

    /**
     *
     * @param array $input
     * @return Response
     */
    public function send($phone, $content)
    {
        try {
            $validator = $this->validator(['phone' => $phone, 'content' => $content]);
            if ($validator->fails()){
                throw new \Exception($validator->errors()->first());
            }

            $dataXml = "<RQST>"
            . "<APIKEY>". $this->APIKEY ."</APIKEY>"
            . "<SECRETKEY>". $this->SECRETKEY ."</SECRETKEY>"
            . "<ISFLASH>0</ISFLASH>"
            . "<SMSTYPE>2</SMSTYPE>"
            . "<CONTENT>" . $content . "</CONTENT>"
            . "<BRANDNAME>" . $this->BRANDNAME . "</BRANDNAME>"
            . "<CONTACTS><CUSTOMER>"
            . "<PHONE>". $phone ."</PHONE>"
            . "</CUSTOMER></CONTACTS>"
            . "</RQST>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->URL_API);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($ch, CURLOPT_POST,           1 );
            curl_setopt($ch, CURLOPT_POSTFIELDS,     $dataXml );
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

            $result     = curl_exec ($ch);
            $xml        = simplexml_load_string($result);

            if ($xml === false) {
                throw new \Exception('Error push api send SMS');
            }
            return ['result'=> 'OK', 'records' => $xml->CodeResult];
        } catch (\Exception $e) {
            return ['result'=> 'NG', 'message' => $e->getMessage()];
        }
    }

    /**
     * Validator input.
     *
     * @param array $input
     * @return JsonResponse
     */
    protected function validator($data)
    {
        return Validator::make($data, [
            'phone' => 'required',
            'content' => 'required'
        ]);
    }
}