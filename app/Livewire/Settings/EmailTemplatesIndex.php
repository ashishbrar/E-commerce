<?php

namespace App\Livewire\Settings;

use App\Models\EmailTemplate;
use Livewire\Component;

class EmailTemplatesIndex extends Component
{
    public function toggleActive(int $id): void
    {
        $template = EmailTemplate::find($id);
        if (! $template) {
            return;
        }

        $template->update(['is_active' => ! $template->is_active]);

        $this->dispatch('flash', type: 'success', message: 'Template '.($template->is_active ? 'enabled' : 'disabled').'.');
    }

    public function render()
    {
        return view('livewire.settings.email-templates-index', [
            'templates' => EmailTemplate::query()->orderBy('name')->get(),
        ]);
    }
}
