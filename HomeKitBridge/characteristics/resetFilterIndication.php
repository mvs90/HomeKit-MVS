<?php

class HAPCharacteristicResetFilterIndication extends HAPCharacteristic
{
    public function __construct()
    {
        parent::__construct(
            0xAD,
            HAPCharacteristicFormat::UnsignedInt8,
            [
                HAPCharacteristicPermission::PairedWrite
            ],
            1,
            1
        );
    }
}
