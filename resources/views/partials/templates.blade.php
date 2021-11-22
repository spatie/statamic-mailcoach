<div class="flex items-center mb-2 mt-6">
    <h2 class="flex-1">Templates</h2>
</div>
@if ($totalTemplatesCount)
    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr class="sortable-row outline-none">
                <x-mailcoach::th sort-by="name" sort-default>Name</x-mailcoach::th>
                <x-mailcoach::th sort-by="-updated_at" class="w-48 th-numeric">Last updated</x-mailcoach::th>
                <th class="w-12"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($templates as $template)
                <tr>
                    <td class="markup-links">
                        <a class="break-words" href="{{ route('mailcoach.templates.edit', $template) }}">
                            {{ $template->name }}
                        </a>
                    </td>
                    <td class="td-numeric">{{ $template->updated_at->toMailcoachFormat() }}</td>
                    <td class="td-action">
                        <div class="dropdown" data-dropdown>
                            <button class="icon-button" data-dropdown-trigger>
                                <i class="fas fa-ellipsis-v | dropdown-trigger-rotate"></i>
                            </button>
                            <ul class="dropdown-list dropdown-list-left | hidden" data-dropdown-list>
                                <li>
                                    <x-mailcoach::form-button
                                        :action="route('mailcoach.templates.delete', $template)"
                                        method="DELETE"
                                        data-confirm="true"
                                    >
                                        <x-mailcoach::icon-label icon="fa-trash-alt" text="Delete" :caution="true" />
                                    </x-mailcoach::form-button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="no-results border-dashed border-2">
        <div class="text-center max-w-md mx-auto mt-5 rounded-lg px-4 py-8">
            <h1 class="my-3">
                Create your first template now
            </h1>
            <p class="text-grey mb-3">
                Mailcoach templates are created and managed through the Mailcoach interface.
            </p>
            <a href="{{ route('mailcoach.templates') }}" class="btn-primary btn-lg">Create template</a>
        </div>
    </div>
@endif
