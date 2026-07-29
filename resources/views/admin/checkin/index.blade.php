@extends('layouts.admin')

@section('title', 'Check-in Scanner Panitia - AmikomEventHub')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="text-center md:text-left">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Aplikasi Penjaga Pintu (Check-in Scanner)</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Arahkan kamera ke QR Code E-Ticket peserta untuk verifikasi kehadiran.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Kamera Scanner --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col items-center">
            <div id="reader" class="w-full rounded-xl overflow-hidden border border-slate-300 dark:border-slate-600 bg-black"></div>
            <p class="text-xs text-slate-400 mt-3 text-center">Pastikan izin kamera diaktifkan pada browser Anda.</p>
        </div>

        {{-- Panel Status & Input Manual --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Status Verifikasi</h2>
            
            {{-- Display Hasil Scan --}}
            <div id="scanResult" class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-center space-y-2 min-h-[140px] flex flex-col justify-center items-center">
                <span class="text-slate-400 text-sm">Menunggu Scan QR Code...</span>
            </div>

            {{-- Fallback Input Manual Order ID --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-2">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400">Atau Masukkan Order ID Manual:</label>
                <form id="manualForm" class="flex gap-2">
                    <input type="text" id="manualOrderId" placeholder="Contoh: TRX-XXXXXXXX"
                        class="flex-1 px-3 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                        Cek
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
{{-- Library HTML5 QR Code Scanner --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resultDiv = document.getElementById('scanResult');
        let isProcessing = false;

        function submitCheckin(orderId) {
            if (isProcessing) return;
            isProcessing = true;

            resultDiv.innerHTML = `<span class="text-indigo-600 dark:text-indigo-400 font-semibold animate-pulse"> Memvalidasi Tiket...</span>`;

            fetch("{{ route('admin.checkin.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                if (res.body.success) {
                    resultDiv.className = "p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 space-y-1 text-left w-full";
                    resultDiv.innerHTML = `
                        <div class="font-bold text-base">✅ ${res.body.message}</div>
                        <div class="text-xs"><strong>Nama:</strong> ${res.body.customer}</div>
                        <div class="text-xs"><strong>Event:</strong> ${res.body.event}</div>
                        <div class="text-xs"><strong>Waktu:</strong> ${res.body.time} WIB</div>
                    `;
                } else {
                    resultDiv.className = "p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-700 text-rose-800 dark:text-rose-300 space-y-1 text-left w-full";
                    resultDiv.innerHTML = `
                        <div class="font-bold text-base">❌ ${res.body.message}</div>
                        ${res.body.customer ? `<div class="text-xs"><strong>Nama:</strong> ${res.body.customer}</div>` : ''}
                    `;
                }
            })
            .catch(() => {
                resultDiv.className = "p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-center";
                resultDiv.innerHTML = `<span>Terjadi kesalahan server saat memproses tiket.</span>`;
            })
            .finally(() => {
                setTimeout(() => { isProcessing = false; }, 2000); // Debounce 2 detik
            });
        }

        // Inisialisasi HTML5 QR Code Scanner
        const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 220, height: 220 } }, false);
        
        html5QrcodeScanner.render((decodedText) => {
            submitCheckin(decodedText);
        }, (error) => {});

        // Form Submit Manual
        document.getElementById('manualForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const val = document.getElementById('manualOrderId').value.trim();
            if (val) submitCheckin(val);
        });
    });
</script>
@endpush