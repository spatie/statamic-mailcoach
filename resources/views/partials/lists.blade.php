<?php /** @var \Spatie\MailcoachSdk\Support\PaginatedResults $lists */ ?>
<div class="flex items-center mb-2 mt-2">
    <h2 class="flex-1">Lists</h2>
</div>
@if ($lists->total())
    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr class="sortable-row outline-none">
                <th>Name</th>
                <th class="text-right">Active</th>
                <th class="w-48 text-right hidden | md:table-cell">Created</th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var \Spatie\MailcoachSdk\Resources\EmailList $emailList */ ?>
            @foreach($lists as $index => $emailList)
                @continue($index > 20)
                <tr>
                    <td class="">
                        <a target="_blank" class="break-words" href="{{ $baseUrl }}/email-lists/{{ $emailList->uuid }}/summary">
                            {{ $emailList->name }}
                        </a>
                    </td>
                    <td class="tabular-nums text-right">{{ number_format($emailList->activeSubscribersCount) }}</td>
                    <td class="tabular-nums text-right hidden | md:table-cell">
                        {{ \Illuminate\Support\Facades\Date::parse($emailList->createdAt)->format('Y-m-d H:i') }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if ($lists->total() > 20)
        <a href="{{ $baseUrl }}/email-lists" class="btn-link underline pl-0 mt-1">View all email lists</a>
    @endif
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
