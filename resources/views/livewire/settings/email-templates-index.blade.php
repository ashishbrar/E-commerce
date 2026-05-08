<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Email Templates') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage transactional email templates (registration, login, password reset, order confirmations).') }}</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('Template List') }}</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="emailTemplatesTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Key') }}</th>
                            <th scope="col">{{ __('Subject') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($templates as $template)
                            <tr>
                                <td class="fw-semibold">{{ $template->name }}</td>
                                <td class="text-muted"><code>{{ $template->key }}</code></td>
                                <td class="text-muted text-truncate" style="max-width: 320px;">{{ $template->subject }}</td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge text-bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge text-bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('settings.email-templates.edit', $template->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                            {{ __('Edit') }}
                                        </a>
                                        <button type="button" wire:click="toggleActive({{ $template->id }})" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-toggle-on"></i>
                                            {{ $template->is_active ? __('Disable') : __('Enable') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    {{ __('No email templates found. Run seeder to create defaults.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#emailTemplatesTable').DataTable();
    });
</script>
