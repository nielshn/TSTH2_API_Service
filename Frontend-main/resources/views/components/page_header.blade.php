<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex align-items-center justify-content-between">
        <div>
            @php
                $subtitle = ucfirst(request()->segment(1) ?? 'Dashboard');
                $title = ucfirst(request()->segment(2) ?? 'Halaman');
            @endphp
            <h4 class="page-title mb-0">
                {{ $title }} - <span class="fw-normal">{{ $subtitle }}</span>
            </h4>
        </div>

        @if (session('login_success'))
            <div id="sweet_success" class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <strong><i class="ph-check-circle"></i> Sukses!</strong> Login berhasil.
            </div>
        @endif
    </div>



    <div class="page-header-content d-lg-flex border-top">
        <div class="d-flex">
            <div class="breadcrumb py-2">
                <a href="{{ url('/') }}" class="breadcrumb-item"><i class="ph-house"></i> Home</a>

                @php
                    $segments = request()->segments();
                    $url = '';
                @endphp

                @foreach ($segments as $index => $segment)
                    @php $url .= '/' . $segment; @endphp

                    @if ($loop->last)
                        <span class="breadcrumb-item active">{{ ucfirst($segment) }}</span>
                    @else
                        <a href="{{ url($url) }}" class="breadcrumb-item">{{ ucfirst($segment) }}</a>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements"></div>
    </div>
</div>
