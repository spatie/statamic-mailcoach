<div class="flex items-center mb-2 mt-6">
    <h2 class="flex-1">Campaigns</h2>
</div>
@if($totalCampaignsCount)
    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr class="sortable-row outline-none">
                <x-mailcoach::th class="w-4"></x-mailcoach::th>
                <x-mailcoach::th sort-by="name">Name</x-mailcoach::th>
                <x-mailcoach::th sort-by="-sent_to_number_of_subscribers" class="w-32 th-numeric">Emails</x-mailcoach::th>
                <x-mailcoach::th sort-by="-unique_open_count" class="w-40 th-numeric hidden | md:table-cell">Unique opens</x-mailcoach::th>
                <x-mailcoach::th sort-by="-unique_click_count" class="w-40 th-numeric hidden | md:table-cell">Unique clicks</x-mailcoach::th>
                <x-mailcoach::th sort-by="-sent" sort-default class="w-40 th-numeric hidden | md:table-cell">Sent</x-mailcoach::th>
            </tr>
            </thead>
            <tbody>
            @foreach($campaigns as $campaign)
                <tr class="sortable-row outline-none" @if($campaign->isSending()) id="campaign-row-{{ $campaign->id }}" data-poll @endif>
                    <td>
                        @include('statamic-mailcoach::partials.campaignStatusIcon')
                    </td>
                    <td class="markup-links">
                        @if($campaign->isSent() || $campaign->isSending())
                            <a href="{{ route('mailcoach.campaigns.summary', $campaign) }}">
                                {{ $campaign->name }}
                            </a>
                        @elseif($campaign->isScheduled())
                            <a href="{{ route('mailcoach.campaigns.delivery', $campaign) }}">
                                {{ $campaign->name }}
                            </a>
                        @else
                            <a href="{{ route('mailcoach.campaigns.settings', $campaign) }}">
                                {{ $campaign->name }}
                            </a>
                        @endif
                    </td>
                    <td class="td-numeric">
                        {{ $campaign->sent_to_number_of_subscribers ?: '-' }}
                    </td>
                    <td class="td-numeric hidden | md:table-cell">
                        @if($campaign->open_rate)
                            {{ $campaign->unique_open_count }}
                            <div class="td-secondary-line">{{ $campaign->open_rate }}%</div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="td-numeric hidden | md:table-cell">
                        @if($campaign->click_rate)
                            {{ $campaign->unique_click_count }}
                            <div class="td-secondary-line">{{ $campaign->click_rate }}%</div>
                        @else
                            -
                    @endif
                    <td class="td-numeric hidden | md:table-cell">
                        @if($campaign->isSent())
                            {{ optional($campaign->sent_at)->toMailcoachFormat() }}
                        @elseif($campaign->isSending())
                            {{ optional($campaign->updated_at)->toMailcoachFormat() }}
                            <div class="td-secondary-line">
                                In progress
                            </div>
                        @elseif($campaign->isScheduled())
                            {{ optional($campaign->scheduled_at)->toMailcoachFormat() }}
                            <div class="td-secondary-line">
                                Scheduled
                            </div>
                        @else
                            -
                        @endif
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
                Create your first campaign now
            </h1>
            <p class="text-grey mb-3">
                Mailcoach campaigns are created and managed through the Mailcoach interface.
            </p>
            <a href="{{ route('mailcoach.campaigns') }}" class="btn-primary btn-lg">Create campaign</a>
        </div>
    </div>
@endif
