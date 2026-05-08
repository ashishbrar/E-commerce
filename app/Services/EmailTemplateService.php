<?php

namespace App\Services;

use App\Models\EmailTemplate;

class EmailTemplateService
{
    /**
     * Fetch an active template by key.
     */
    public function find(string $key): ?EmailTemplate
    {
        return EmailTemplate::where('key', $key)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Render the subject + body of a template by key, replacing
     * {{placeholder}} tokens with values from $data. Returns null if
     * the template does not exist or is inactive.
     *
     * @param  array<string, string|int|float|null>  $data
     * @return array{subject: string, body: string}|null
     */
    public function render(string $key, array $data = []): ?array
    {
        $template = $this->find($key);

        if (! $template) {
            return null;
        }

        return [
            'subject' => $this->replace($template->subject, $data),
            'body'    => $this->replace($template->body, $data),
        ];
    }

    /**
     * Replace {{token}} placeholders in $text with values from $data.
     */
    public function replace(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('{{'.$key.'}}', (string) $value, $text);
        }

        return $text;
    }
}
