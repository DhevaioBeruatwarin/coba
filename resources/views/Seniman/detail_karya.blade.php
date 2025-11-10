@extends('layouts.app')

@section('title', $karya->nama_karya)

@section('content')
<link rel="stylesheet" href="{{ asset('css/Seniman/detail_karya.css') }}">

<div class="karya-detail-container">
    <div class="karya-detail-card">
        <div class="image-section">
            <img src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}">
        </div>

        <div class="info-section">
            <h1 class="karya-title">{{ $karya->nama_karya }}</h1>

            <div class="seniman-info">
                <p>by <strong>{{ $karya->seniman->nama }}</strong></p>
            </div>

            @if(isset($karya->rating) && $karya->rating > 0)
                <div class="rating">
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= $karya->rating ? 's' : 'r' }} fa-star"></i>
                        @endfor
                    </span>
                    <span class="rating-text">{{ number_format($karya->rating, 1) }}/5.0</span>
                </div>
            @endif

            <p class="karya-description">{{ $karya->deskripsi }}</p>

            <p class="price">Rp {{ number_format($karya->harga, 0, ',', '.') }}</p>

            <button class="btn-beli">Beli Sekarang</button>
        </div>
    </div>

    @if($karyaSeniman->count() > 0)
    <div class="related-section">
        <h2>Karya Lain dari {{ $karya->seniman->nama }}</h2>
        <div class="related-grid">
            @foreach($karyaSeniman as $item)
                <a href="{{ route('karya.detail', $item->kode_seni) }}" class="related-card">
                    <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                    <div class="related-info">
                        <p class="related-title">{{ $item->nama_karya }}</p>
                        <p class="related-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($karyaLainnya->count() > 0)
    <div class="related-section">
        <h2>Karya Lainnya</h2>
        <div class="related-grid">
            @foreach($karyaLainnya as $item)
                <a href="{{ route('karya.detail', $item->kode_seni) }}" class="related-card">
                    <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                    <div class="related-info">
                        <p class="related-title">{{ $item->nama_karya }}</p>
                        <p class="related-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
