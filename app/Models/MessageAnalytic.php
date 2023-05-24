<?php

namespace App\Models;

use App\Models\Scopes\MessageAnalyticScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenAI;
use Aws\ComprehendMedical\ComprehendMedicalClient;
use Exception;
use Illuminate\Http\Request;
use faker\Factory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Venturecraft\Revisionable\RevisionableTrait;


class MessageAnalytic extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RevisionableTrait;

    private $generatedData = array(); // Array to store generated fake data

    private $comprehendMedicalClient; // Class property to store ComprehendMedicalClient object

    private $openAIClient; // Class property to store OpenAI client object

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Initialize ComprehendMedicalClient object
        $this->comprehendMedicalClient = new ComprehendMedicalClient([
            'region' => config('red-queen-ivf-ai.aws_default_region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('red-queen-ivf-ai.aws_access_key'),
                'secret' => config('red-queen-ivf-ai.aws_secret_access_key'),
            ],
        ]);

        // Initialize openAIClient object
        $this->openAIClient = OpenAI::client(config('red-queen-ivf-ai.open_ai_key'));
    }

    protected $fillable = [
        'user_id',
        'input_message',
        'output_message',
        'masked_data',
        'phi_detect_data',
        'message_owner_id',
        'response_feedback'
    ];

    protected $casts = [
        'phi_detect_data' => 'array',
        'output_message' => 'array',
        'response_feedback' => 'array'
    ];

    protected static function boot()
    {
        static::addGlobalScope(new MessageAnalyticScope);
        parent::boot();
        static::saving(function ($model) {
            try {
                // if response feedback then only add that in existing model
                if ($model->response_feedback) return $model;

                $model->phi_detect_data = $model->getPhiDetectData($model->input_message) ?: (object)[];

                $model->masked_data = $model->maskPhiData($model->phi_detect_data, $model->input_message) ?: "";

                $model->output_message =  $model->getOpenAIResponse($model->masked_data) ?: (object)[];
            } catch (Exception $e) {
                Log::error('Error while processing message: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'code' => 500,
                ], 500);
            }
        });
    }


    public function getPhiDetectData($message)
    {
        try {
            // Detect PHI (Protected Health Information) in the input message using ComprehendMedical
            $comprehendData = $this->comprehendMedicalClient->DetectPHI([
                'Text' => $message,
            ]);

            $entities = $comprehendData->get('Entities');

            $entitiesData = json_decode(json_encode($entities));
            return $entitiesData;
        } catch (Exception $e) {
            Log::error('Error while detecting PHI: ' . $e->getMessage());
            return (object)[];
        }
    }

    public function getOpenAIResponse($maskedPhiDetectedData)
    {
        try {
            $prompt = "
                Based on the above, please generate a JSON object with the following fields:
                - emotion (object): An object containing emotional responses with the following fields:
                    - Happy (float): A value between 0.0 and 1.0 representing the patient's level of happiness
                    - Neutral (float): A value between 0.0 and 1.0 representing the patient's level of neutral
                    - Sad (float): A value between 0.0 and 1.0 representing the patient's level of sadness
                    - Anxious (float): A value between 0.0 and 1.0 representing the patient's level of anxiety
                    - Angry (float): A value between 0.0 and 1.0 representing the patient's level of anger
                - urgency (object): An object containing the level of urgency with the following fields:
                    - Critical (float): A value between 0.0 and 1.0 representing the level of critical
                    - Urgent (float): A value between 0.0 and 1.0 representing the level of urgent
                    - Routine (float): A value between 0.0 and 1.0 representing the level of routine
                - possible_response (string): A simplified proposed response or treatment plan based on patient's symptoms explained in simple terms to a person with no medical education
            ";
            // Generate a diagnosis and treatment plan using OpenAI's gpt-3.5-turbo model
            $result =  $this->openAIClient->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $maskedPhiDetectedData . " " . $prompt,
                    ],
                ],
            ]);
            $diagnosisAndTreatmentPlan = $result->choices[0]->message->content ?: (object)[];
            // Unmask the final response
            $unmaskedDiagnosisAndTreatmentPlan = json_decode($this->unmaskPhiData($diagnosisAndTreatmentPlan));
            return [$unmaskedDiagnosisAndTreatmentPlan];
        } catch (Exception $e) {
            Log::error('Error while generating open ai response: ' . $e->getMessage());
            return (object)[];
        }
    }

    public function maskPhiData($entities, $text)
    {
        $faker = Factory::create();

        // Create a map of entity types to fake data generators.
        $fakeData = [
            'NAME' => fn ($faker) => $faker->firstName . ' ' . $faker->lastName,
            'PROFESSION' => fn ($faker) => $faker->jobTitle,
            'ADDRESS' => fn ($faker) => $faker->streetAddress . ', ' . $faker->city . ', ' . $faker->state,
            'DATE' => function ($faker, $entityText) {
                // Extract the year from the original entity text and generate a random date within that year.
                $originalYear = substr($entityText, -4);
                return $faker->dateTimeBetween($originalYear . '-01-01', $originalYear . '-12-31')->format('F d, Y');
            }
        ];

        // Generate fake data for each PHI entity.
        foreach ($entities as &$entity) {
            $entityText = $entity['Text'];
            $entityType = $entity['Type'];

            if (isset($fakeData[$entityType])) {
                $fakeDataGenerator = $fakeData[$entityType];

                $fakeValue = $fakeDataGenerator($faker, $entityText);

                $entity['Text'] = $fakeValue;
                $this->generatedData[] = ['type' => $entityType, 'original' => $entityText, 'fake' => $fakeValue];
            }
        }

        // Replace original PHI entities in the text with the generated fake data.
        foreach ($this->generatedData as $generatedData) {
            $text = str_replace($generatedData['original'], $generatedData['fake'], $text);
        }

        return $text;
    }


    public function unmaskPhiData($text)
    {
        // Replace the fake data in the input text with the corresponding original data
        foreach ($this->generatedData as $generatedData) {
            $text = str_replace($generatedData['fake'], $generatedData['original'], $text);
        }
        return $text;
    }

    public static function saveUserFeedback(Request $request, $feedback)
    {
        // Extract the feedback data from the request and save it to the feedback object.
        $feedback->response_feedback = [
            'emotion' => $request->emotion,
            'critical' => $request->critical,
            'possible_response' => $request->possible_response,
        ];

        $feedback->save();

        // Return a JSON response with success.
        return response()->json([
            'success' => true,
            'message' => $feedback->response_feedback,
            'code' => 200
        ], 200);
    }


    public static function saveMessageAnalyticFromRequest(Request $request)
    {
        try {
            // Create a new message analytic object.
            $message = static::create([
                'input_message' => $request->message_text,
                'message_owner_id' => $request->message_owner_id,
                'user_id' => $request->user_id,
            ]);

            // Check if output_message is null or empty.
            if (empty($message->output_message)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unable to generate response',
                    'code' => 500,
                ], 500);
            }

            // Return a JSON response with success.
            return response()->json([
                'success' => true,
                'id' => $message->id,
                'message' => $message->output_message,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error while creating message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => 500,
            ], 500);
        }
    }


    public static function getAnalyticMessage($message)
    {
        // Retrieve the output message from the $message object.
        $responseData = [
            'success' => true,
            'message' => $message->output_message,
            'code' => 200
        ];

        return response()->json($responseData, 200);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
