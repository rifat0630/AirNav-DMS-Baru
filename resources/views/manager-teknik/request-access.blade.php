@extends('layouts.app')

@section('content')
<div class="py-12 max-w-xl mx-auto px-4">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
            🔒
        </div>
        <h2 class="text-xl font-bold text-gray-800">Izin Manager Teknik Diperlukan</h2>
        <p class="text-xs text-gray-500 mt-2">
            Halaman ini merupakan kewenangan khusus Mantek. Anda memerlukan persetujuan dari Mantek sebelum dapat masuk.
        </p>

        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 text-xs p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($lastRequest && $lastRequest->status === 'pending')
            <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-xl text-xs text-left">
                <p class="font-semibold">⏳ Permintaan Izin Sedang Diproses Mantek</p>
                <p class="mt-1 text-gray-600">Alasan: "{{ $lastRequest->reason }}"</p>
                <p class="mt-1 text-[11px] text-gray-400">Diajukan: {{ $lastRequest->created_at->format('d M Y, H:i') }}</p>
            </div>
        @elseif($lastRequest && $lastRequest->status === 'rejected')
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs text-left">
                <p class="font-semibold">❌ Permintaan Terakhir Ditolak oleh Mantek</p>
            </div>
        @endif

        @if(!$lastRequest || $lastRequest->status !== 'pending')
            <form action="{{ route('mantek.access.submit') }}" method="POST" class="mt-6 text-left space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Mengakses Menu Mantek</label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Perlu mengecek catatan operasional radar..." class="w-full text-xs border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-xs shadow">
                    Kirim Permintaan Izin ke Mantek
                </button>
            </form>
        @endif

        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-xs text-gray-400 hover:text-gray-600">← Kembali ke Dashboard Utama</a>
        </div>
    </div>
</div>
@endsection