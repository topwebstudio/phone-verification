<?php

namespace Topwebstudio\PhoneVerification;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberParseException;
use Brick\PhoneNumber\PhoneNumberFormat;

class Verification {

    protected function getPhoneData($phone) {

        $error = '';
        try {
            $number = PhoneNumber::parse($phone);
        } catch (PhoneNumberParseException $e) {
            $error = $e->getMessage();
        }

        $data = [
            'isValid' => '',
            'countryCode' => '',
            'internationalPhone' => '',
            'phone' => '',
            'internationalPhoneNormalized' => '',
            'phoneNormalized' => '',
            'type' => '',
            'errorMessage' => $error
        ];

        if (!$error && $number->isValidNumber()) {
            $internationalPhoneFormatted = $number->format(PhoneNumberFormat::E164);
            $phone = $number->format(PhoneNumberFormat::NATIONAL);
            $type = PhoneNumber::parse($internationalPhoneFormatted)->getNumberType();

            $data = [
                'isValid' => true,
                'countryCode' => $number->getRegionCode(),
                'internationalPhone' => $internationalPhoneFormatted,
                'phone' => $number->format(PhoneNumberFormat::NATIONAL),
                'internationalPhoneNormalized' => preg_replace("/[^0-9]/", "", $internationalPhoneFormatted),
                'phoneNormalized' => preg_replace("/[^0-9]/", "", $phone),
                'type' => $type,
                'isMobile' => in_array($type, [1, 2]) ? true : false
            ];
        }

        return $data;
    }

}
