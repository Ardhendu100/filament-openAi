<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class MessageAnalyticRule implements Rule
{
    public function passes($attribute, $value)
    {
        return true;
    }

    public function message()
    {
        return 'The :attribute is invalid.';
    }
    public static function rules()
    {
        return [
            'message_text' => ['required','string', new self],
            'message_owner_id' => ['required', 'string', new self],
        ];
    }
    public static function feedbackRules()
    {
        return [
            'emotion' => ['string', ValidationRule::in(['accurate', 'inaccurate', 'semi-accurate'])],
            'critical' => ['string', ValidationRule::in(['accurate', 'inaccurate', 'semi-accurate'])],
            'possible_response' => ['string', ValidationRule::in(['accurate', 'inaccurate', 'semi-accurate'])],
        ];
    }
}
