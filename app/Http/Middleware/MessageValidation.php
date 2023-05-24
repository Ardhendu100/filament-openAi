<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class MessageValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response|JsonResponse
    {
        $validation_methods = [
            'feedback' => 'validateFeedback',
            'message' => 'validateMessage',
        ];

        $this->{$validation_methods[$type] ?? throw new \InvalidArgumentException("Invalid validation type: {$type}")}($request);

        return $next($request);
    }

    private function validateFeedback(Request $request): void
    {
        $rules = $this->getFeedbackValidationRules();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw new ValidationException($validator, response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
                'code' => 400,
            ], 400));
        }
    }

    private function validateMessage(Request $request): void
    {
        $rules = $this->getMessageValidationRules();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw new ValidationException($validator, response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
                'code' => 400,
            ], 400));
        }
    }

    private function getFeedbackValidationRules(): array
    {
        return [
            'emotion' => ['required','string', 'in:accurate,inaccurate,semi-accurate'],
            'critical' => ['required','string', 'in:accurate,inaccurate,semi-accurate'],
            'possible_response' => ['required','string', 'in:accurate,inaccurate,semi-accurate'],
        ];
    }

    private function getMessageValidationRules(): array
    {
        return [
            'message_text' => ['required', 'string'],
            'message_owner_id' => ['required', 'string'],
        ];
    }
}
