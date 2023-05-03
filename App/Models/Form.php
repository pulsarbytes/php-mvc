<?php
namespace App\Models;

use App\Config;
use OpenAI;

/**
 * Form model.
 */
class Form
{
    /**
     * Get title from form post data.
     *
     * @return string
     */
    public static function getTitle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if (isset($_POST['title']))
            {
                // Sanitize the input using htmlspecialchars
                return htmlspecialchars($_POST['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        return '';
    }

    /**
     * Generate content via the OpenAI API.
     *
     * @param string $title The prompt to use in the OpenAI API
     *
     * @return string
     */
    public static function generateContent($title)
    {
        $api_key = Config::OPENAI_API_KEY;
        $client = OpenAI::client($api_key);

        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'Generate a paragraph of 100 words for the following post title: '.$title,
        ]);

        $content = $result['choices'][0]['text'];

        // Sanitize the input using htmlspecialchars
        $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $content;
    }
}