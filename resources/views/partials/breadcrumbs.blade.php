@if (count($breadcrumbs))
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @endif

        @endforeach
    </ol>

@endif