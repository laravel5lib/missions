<?php
namespace App;

class ReservationFactory
{
    public static function make($request)
    {
        $weight = static::convertWeight($request->only(['weight', 'country_code']));
        $height = static::convertHeight($request->only(['height_a', 'height_b']));

        return [
            'given_names' => trim($request->get('given_names')),
            'surname' => trim($request->get('surname')),
            'gender' => $request->get('gender'),
            'status' => $request->get('status'),
            'shirt_size' => $request->get('shirt_size'),
            'birthday' => $request->get('birthday'),
            'phone_one' => stripPhone($request->get('phone_one')),
            'phone_two' => stripPhone($request->get('phone_two')),
            'address' => trim(ucwords(strtolower($request->get('address')))),
            'city' => trim(ucwords(strtolower($request->get('city')))),
            'state' => trim(ucwords(strtolower($request->get('state')))),
            'zip' => trim(strtoupper($request->get('zip'))),
            'country_code' => $request->get('country_code'),
            'user_id' => $request->get('user_id'),
            'email' => trim(strtolower($request->get('email'))),
            'desired_role' => $request->get('desired_role'),
            'shirt_size' => $request->get('shirt_size'),
            'height' => $height,
            'weight' => $weight,
            'avatar_upload_id' => $request->get('avatar_upload_id'),
            'companion_limit' => $request->get('companion_limit')
        ];
    }

    private static function convertWeight($request)
    {
        $weight = $request['weight']; // kilograms

        if ($request['country_code'] == 'us') {
            $weight = convert_to_kg($request['weight']);
        }

        return $weight;
    }

    private static function convertHeight($request)
    {
        $height = (int) $request['height_a'].$request['height_b']; // centimeters
        $height = convert_to_cm($request['height_a'], $request['height_b']);

        return $height;
    }
}