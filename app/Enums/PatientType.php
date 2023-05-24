<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Patient()
 * @method static static Partner()
 * @method static static Egg donor()
 * @method static static Surrogate()

 */
final class PatientType extends Enum
{
   
    const MALE = 'male';
    const FEMALE = 'female';
    const OTHER = 'other';

    public static function getStatuses()
    {
        return [
            self::MALE()->value => __('gametes-witnessing-system::gametes-witnessing-system.gender.male'),
            self::FEMALE()->value => __('gametes-witnessing-system::gametes-witnessing-system.gender.female'),
            self::OTHER()->value => __('gametes-witnessing-system::gametes-witnessing-system.gender.other'),

        ];
    }
}
