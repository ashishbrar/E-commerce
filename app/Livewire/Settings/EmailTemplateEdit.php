<?php

namespace App\Livewire\Settings;

use App\Models\EmailTemplate;
use Livewire\Component;

class EmailTemplateEdit extends Component
{
    public EmailTemplate $template;
    public string $name = '';
    public string $subject = '';
    public string $body = '';
    public bool $isActive = true;

    public function mount(int $id): void
    {
        $this->template = EmailTemplate::findOrFail($id);
        $this->name = $this->template->name;
        $this->subject = $this->template->subject;
        $this->body = $this->template->body;
        $this->isActive = (bool) $this->template->is_active;
    }

    public function save()
    {
        $this->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'isActive' => ['boolean'],
        ]);

        $this->template->update([
            'subject' => $this->subject,
            'body' => $this->body,
            'is_active' => $this->isActive,
        ]);

        session()->flash('success', 'Email template updated successfully.');

        return redirect()->route('settings.email-templates');
    }

    public function render()
    {
        return view('livewire.settings.email-template-edit');
    }
}
