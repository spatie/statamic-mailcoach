@switch ($campaign->status)
    @case('draft')
        @if($campaign->scheduled_at)
            <i title="Scheduled" class="text-orange inline-block w-3">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12V8.25M12 12l4.687 4.688"/></svg>
            </i>
        @else
            <i title="Draft" class="text-grey-70 inline-block w-3">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11 18.5H1.5a1 1 0 0 1-1-1v-16a1 1 0 0 1 1-1h15a1 1 0 0 1 1 1v9.965" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><rect x="2.5" y="2.5" width="13" height="5" rx=".5" ry=".5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.5 10v6a.5.5 0 0 0 .5.5h6.337a.5.5 0 0 0 .481-.637l-1.714-6a.5.5 0 0 0-.481-.363H3a.5.5 0 0 0-.5.5zM15.5 13.5V10a.5.5 0 0 0-.5-.5h-3.837a.5.5 0 0 0-.481.637L12.5 16.5h.458M15.7 22.3l-4.2 1.2 1.2-4.2 7.179-7.179a2.121 2.121 0 0 1 3 3zM18.979 13.021l3 3M12.7 19.3l3 3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </i>
        @endif
    @break
    @case('sent')
        <i title="Sent" class="text-green inline-block w-3">
            <svg class="fill-current" viewBox="0 0 24 24"><g transform="matrix(1,0,0,1,0,0)"><path d="M23.146,5.4l-2.792-2.8c-0.195-0.196-0.512-0.196-0.707-0.001c0,0-0.001,0.001-0.001,0.001L7.854,14.4 c-0.195,0.196-0.512,0.196-0.707,0.001c0,0-0.001-0.001-0.001-0.001l-2.792-2.8c-0.195-0.196-0.512-0.196-0.707-0.001 c0,0-0.001,0.001-0.001,0.001l-2.792,2.8c-0.195,0.195-0.195,0.512,0,0.707L7.146,21.4c0.195,0.196,0.512,0.196,0.707,0.001 c0,0,0.001-0.001,0.001-0.001L23.146,6.1C23.337,5.906,23.337,5.594,23.146,5.4z" stroke="none" fill="currentColor" stroke-width="0" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
        </i>
    @break
    @case('sending')
        <i title="Sending" class="text-blue inline-block w-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.5 19.5a2 2 0 0 1-2 2h-19a2 2 0 0 1-2-2v-14a2 2 0 0 1 2-2h19a2 2 0 0 1 2 2zM.5 8.498h23" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.5 15A2.5 2.5 0 0 1 6 12.5h12a2.5 2.5 0 0 1 0 5H6A2.5 2.5 0 0 1 3.5 15zm2.041 2.455l4.957-4.957m-.5 5l5-5m-10.5-9v5m4-5v5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </i>
    @break
@endswitch
