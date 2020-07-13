<div class="flex items-center mb-2 mt-6">
    <h2 class="flex-1">Lists</h2>
</div>
@if ($totalListsCount)
    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr class="sortable-row outline-none">
                <x-th sort-by="name" sort-default>Name</x-th>
                <x-th sort-by="-active_subscribers_count" class="w-32 th-numeric">Active</x-th>
                <x-th sort-by="-created_at" class="w-48 th-numeric hidden | md:table-cell">Created</x-th>
                <th class="w-12"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($lists as $emailList)
                <tr>
                    <td class="markup-links">
                        <a class="break-words" href="{{ route('mailcoach.emailLists.subscribers', $emailList) }}">
                            {{ $emailList->name }}
                        </a>
                    </td>
                    <td class="td-numeric">{{ $emailList->active_subscribers_count }}</td>
                    <td class="td-numeric hidden | md:table-cell">
                        {{ $emailList->created_at->toMailcoachFormat() }}
                    </td>
                    <td class="td-action">
                        <div class="dropdown" data-dropdown>
                            <button class="icon-button" data-dropdown-trigger>
                                <i class="fas fa-ellipsis-v | dropdown-trigger-rotate"></i>
                            </button>
                            <ul class="dropdown-list dropdown-list-left | hidden" data-dropdown-list>
                                <li>
                                    <x-form-button
                                            :action="route('mailcoach.emailLists.delete', $emailList)"
                                            method="DELETE"
                                            data-confirm="true"
                                    >
                                        <x-icon-label icon="fa-trash-alt" text="Delete" :caution="true" />
                                    </x-form-button>
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
                Create your first email list now
            </h1>
            <p class="text-grey mb-3">
                Mailcoach email lists are created and managed through the Mailcoach interface.
            </p>
            <a href="{{ route('mailcoach.emailLists') }}" class="btn-primary btn-lg">Create list</a>
        </div>
    </div>
@endif
