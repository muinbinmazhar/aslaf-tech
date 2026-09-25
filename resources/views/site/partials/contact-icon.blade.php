<svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($icon)
        @case('phone')<path d="M5 3h4l1 5-2 2a16 16 0 0 0 6 6l2-2 5 1v4a2 2 0 0 1-2 2A17 17 0 0 1 3 5a2 2 0 0 1 2-2Z"/>@break
        @case('mail')<rect x="2" y="5" width="20" height="14" rx="2"/><path d="m3 7 9 7 9-7"/>@break
        @case('pin')<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>@break
        @case('globe')<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c-5 5-5 13 0 18M12 3c5 5 5 13 0 18"/>@break
        @case('chat')<path d="M21 11a8 8 0 0 1-8 8H7l-4 3v-6a8 8 0 1 1 18-5Z"/><path d="M8 11h8"/>@break
        @case('video')<rect x="2" y="5" width="20" height="14" rx="3"/><path d="m10 9 5 3-5 3V9Z"/>@break
        @case('code')<path d="m9 7-5 5 5 5m6-10 5 5-5 5m-2-13-4 18"/>@break
        @case('creative')<circle cx="12" cy="12" r="9"/><circle cx="8" cy="9" r="1"/><circle cx="15" cy="8" r="1"/><path d="M8 16c2-3 5-3 8-1"/>@break
        @case('work')<rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 13h18"/>@break
        @case('store')<path d="M3 9h18l-2-6H5L3 9Zm2 0v12h14V9M9 21v-7h6v7"/>@break
        @case('directions')<path d="m3 12 9-9 9 9-9 9-9-9Zm5 1h8m-3-3 3 3-3 3"/>@break
        @case('facebook')<path d="M14 21v-8h3l.5-4H14V7c0-1.1.5-1.5 1.5-1.5H18V2.3A20 20 0 0 0 15.5 2C12.5 2 11 3.8 11 6.8V9H8v4h3v8"/>@break
        @case('instagram')<rect x="2.5" y="2.5" width="19" height="19" rx="5"/><circle cx="12" cy="12" r="4.25"/><circle cx="17.5" cy="6.5" r=".75" fill="currentColor" stroke="none"/>@break
        @case('social')<circle cx="8" cy="9" r="3"/><circle cx="17" cy="10" r="2"/><path d="M2 20a6 6 0 0 1 12 0m1-5a5 5 0 0 1 7 5"/>@break
        @default<path d="M10 13a5 5 0 0 0 8 .5l2-2a5 5 0 0 0-7-7l-1 1M14 11a5 5 0 0 0-8-.5l-2 2a5 5 0 0 0 7 7l1-1"/>
    @endswitch
</svg>
