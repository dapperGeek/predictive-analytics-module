<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI;

class PredictiveAnalyticsController extends Controller
{
    /**
     * Predict customer churn using OpenAI.
     */
    public function predictChurn(Request $request): JsonResponse
    {
        // Validate input data
        $validated = $request->validate([
            'age' => 'required|numeric',
            'avg_monthly_activity' => 'required|numeric',
            'payment_history_score' => 'required|numeric',
            'feature_4' => 'required|numeric',
            'feature_5' => 'required|numeric',
        ]);

        // Prepare the prompt for OpenAI
        $prompt = "
        You are an expert data analyst. Predict customer churn based on the following data:
        - Age: {$validated['age']}
        - Average Monthly Activity: {$validated['avg_monthly_activity']}
        - Payment History Score: {$validated['payment_history_score']}
        - Feature 4: {$validated['feature_4']}
        - Feature 5: {$validated['feature_5']}

        Return the churn probability as a percentage (e.g., 78%).
        ";

        // Query OpenAI API
        $client = OpenAI::client(env('OPENAI_API_KEY'));
        $response = $client->completions()->create([
            'model' => 'gpt-3.5-turbo', // Use 'gpt-4' or 'gpt-3.5-turbo' if available
            'prompt' => $prompt,
            'max_tokens' => 100,
            'temperature' => 0.7,
        ]);

        // Extract prediction from the response
        $prediction = $response['choices'][0]['text'] ?? 'Unable to generate prediction';

        // Clean up response text
        $prediction = trim($prediction);

        return response()->json([
            'success' => true,
            'churn_probability' => $prediction,
        ]);
    }
}
