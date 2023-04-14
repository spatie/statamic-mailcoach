<?php /** @var \Spatie\MailcoachSdk\Support\PaginatedResults $campaigns */ ?>
<div class="flex items-center mb-2 mt-2">
    <h2 class="flex-1">Campaigns</h2>
</div>
@if($campaigns->total())
    <div class="card p-0">
        <table class="data-table">
            <thead>
                <tr class="sortable-row outline-none">
                    <th class="w-4"></th>
                    <th>Name</th>
                    <th class="text-right">Emails</th>
                    <th class="text-right">Unique opens</th>
                    <th class="text-right">Unique clicks</th>
                    <th class="text-right">Sent</th>
                </tr>
            </thead>
            <tbody>
            <?php /** @var \Spatie\MailcoachSdk\Resources\Campaign $campaign */ ?>
            @foreach($campaigns as $index => $campaign)
                @continue($index > 15)
                <tr class="sortable-row outline-none">
                    <td>
                        @include('statamic-mailcoach::partials.campaignStatusIcon')
                    </td>
                    <td class="markup-links">
                        @if($campaign->status === 'sent' || $campaign->status === 'sending')
                            <a target="_blank" href="{{ $baseUrl }}/campaigns/{{ $campaign->uuid }}/summary">
                                {{ $campaign->name }}
                            </a>
                        @elseif($campaign->scheduledAt)
                            <a target="_blank" href="{{ $baseUrl }}/campaigns/{{ $campaign->uuid }}/delivery">
                                {{ $campaign->name }}
                            </a>
                        @else
                            <a target="_blank" href="{{ $baseUrl }}/campaigns/{{ $campaign->uuid }}/settings">
                                {{ $campaign->name }}
                            </a>
                        @endif
                    </td>
                    <td class="tabular-nums text-right">
                        {{ $campaign->sentToNumberOfSubscribers ? number_format($campaign->sentToNumberOfSubscribers) : '-' }}
                    </td>
                    <td class="tabular-nums text-right">
                        @if($campaign->openRate)
                            {{ number_format($campaign->uniqueOpenCount) }}
                            <div class="text-xs text-gray">{{ number_format($campaign->openRate / 100) }}%</div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="tabular-nums text-right">
                        @if($campaign->clickRate)
                            {{ number_format($campaign->uniqueClickCount) }}
                            <div class="text-xs text-gray">{{ number_format($campaign->clickRate / 100) }}%</div>
                        @else
                            -
                    @endif
                    <td class="tabular-nums text-right">
                        @if($campaign->status === 'sent')
                            <time datetime="{{ $campaign->sentAt }}">{{ \Illuminate\Support\Facades\Date::parse($campaign->sentAt)->diffForHumans() }}</time>
                        @elseif($campaign->status === 'sending')
                            <time datetime="{{ $campaign->updatedAt }}">{{ \Illuminate\Support\Facades\Date::parse($campaign->updatedAt)->diffForHumans() }}</time>
                            <div class="text-xs text-gray">
                                In progress
                            </div>
                        @elseif($campaign->scheduledAt)
                            <time datetime="{{ $campaign->scheduledAt }}">{{ \Illuminate\Support\Facades\Date::parse($campaign->scheduledAt)->diffForHumans() }}</time>
                            <div class="text-xs text-gray">
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
    @if ($campaigns->total() > 15)
        <a href="{{ $baseUrl }}/campaigns" class="btn-link underline pl-0 mt-1">View all campaigns</a>
    @endif
@else
    <div class="no-results border-dashed border-2">
        <div class="text-center max-w-md mx-auto mt-5 rounded-lg px-4 py-8">
            <h1 class="my-3">
                Create your first Campaign
            </h1>
            <p class="text-grey mb-3">
                Mailcoach campaigns are created and managed through Mailcoach.
            </p>
            <a href="{{ $baseUrl }}/campaigns" class="btn-primary btn-lg">Create Campaign</a>
        </div>
    </div>
@endif
