<?php

namespace App\Helpers;

use App\Helpers\ArrayHelper;

Class ValidationHelper {

    public function __construct()
    {
        $this->arrayHelper = new ArrayHelper;
    }

    public function validate($request, $validation)
    {
        $validator = \Validator::make($request, $validation);

        if ($validator->fails()) {
            $error = $validator->messages()->toArray();
            $error = $this->arrayHelper->array_flatten($error);
            $error = implode(' ', $error);

            return [
                "meta" => [
                    "code" => 400,
                    "message" => $error
                ]
            ];
        }

        return true;
    }

    public function response($validator)
    {
        // $validator = \Validator::make($request, $validation);

        // if ($validator->fails()) {
            $error = $validator->messages()->toArray();
            $error = $this->arrayHelper->array_flatten($error);
            $error = implode(' ', $error);
            $error= str_replace('firstname', 'Nama depan',$error);
            $error= str_replace('phone', 'Nomor telephone',$error);
            $error= str_replace('email', 'Email',$error);
            $error= str_replace('Nomor Telephone sudah digunakan email sudah digunakan', 'Nomor Telephone sudah digunakan',$error);
            // dd($a);
            

            return [
                "meta" => [
                    "code" => 400,
                    "message" => $error
                ]
            ];
        // }

        return true;
    }

}