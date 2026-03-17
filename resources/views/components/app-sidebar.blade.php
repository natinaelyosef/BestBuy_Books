@props([
    'brand' => 'BestBuy_Books',
    'brandIcon' => 'bi bi-book-half',
    'sections' => [],
])

<aside class="app-sidebar" id="{{ $attributes->get('id', 'sidebar') }}">
    <div class="sidebar-brand">
        <span class="brand-icon"><i class="{{ $brandIcon }}"></i></span>
        <span class="brand-title">{{ $brand }}</span>
    </div>

    @foreach ($sections as $section)
        <div class="sidebar-section">
            @if (!empty($section['label']))
                <div class="sidebar-section-title">{{ $section['label'] }}</div>
            @endif
            <nav class="sidebar-links">
                @foreach ($section['items'] ?? [] as $item)
                    @php
                        $href = $item['url'] ?? (isset($item['route']) ? route($item['route']) : '#');
                        $active = $item['active'] ?? false;
                        $badge = $item['badge'] ?? null;
                    @endphp
                    <a href="{{ $href }}" class="sidebar-link {{ $active ? 'active' : '' }}">
                        <i class="{{ $item['icon'] ?? 'bi bi-circle' }}"></i>
                        <span>{{ $item['label'] ?? 'Item' }}</span>
                        @if (!empty($badge))
                            <span class="sidebar-badge">{{ $badge }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>
    @endforeach

    <div class="sidebar-footer">
        {{ $slot }}
    </div>
</aside>
