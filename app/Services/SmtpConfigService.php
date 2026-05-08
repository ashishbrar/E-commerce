<?php

namespace App\Services;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;

class SmtpConfigService
{
    /**
     * Pull the latest active SMTP setting from the database and apply
     * it to the runtime mail config. Safe to call before any send;
     * silently returns false if SMTP is not configured.
     */
    public function apply(): bool
    {
        $smtp = SmtpSetting::latest('id')->first();

        if (! $smtp || ! $smtp->is_active || ! $smtp->mail_host || ! $smtp->mail_from_address) {
            return false;
        }

        $scheme = match ($smtp->mail_encryption) {
            'ssl', 'smtps' => 'smtps',
            default => 'smtp',
        };

        Config::set('mail.default', $smtp->mail_mailer ?: 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->mail_host);
        Config::set('mail.mailers.smtp.port', (int) $smtp->mail_port);
        Config::set('mail.mailers.smtp.username', $smtp->mail_username);
        Config::set('mail.mailers.smtp.password', $smtp->mail_password);
        Config::set('mail.mailers.smtp.scheme', $scheme);
        Config::set('mail.mailers.smtp.encryption', null);
        Config::set('mail.from.address', $smtp->mail_from_address);
        Config::set('mail.from.name', $smtp->mail_from_name ?: 'AceStore');

        if (method_exists(app('mail.manager'), 'forgetMailers')) {
            app('mail.manager')->forgetMailers();
        }

        return true;
    }
}
