<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Voucher Bundle {{ $bundle->bundle_number }}</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Arial', 'Helvetica', 'sans-serif'], // Font cetak formal
                        mono: ['"Courier New"', 'Courier', 'monospace'], // Untuk isian
                    },
                }
            }
        }
    </script>
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 0;
                /* Menghilangkan Header/Footer Browser (URL, Tgl, dll) */
            }

            body {
                margin: 0;
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page-break {
                page-break-after: always;
                margin: 0;
                padding-top: 0.5cm;
                /* Jarak aman atas dari pinggir kertas */
            }

            .no-print {
                display: none !important;
            }

            .garis-hitam {
                border-color: #000 !important;
            }
        }

        body {
            background-color: #f1f5f9;
        }

        /* Latar belakang UI luar kertas */
    </style>
</head>

<body class="text-black antialiased">
    @php
        // Helper Terbilang
        if (!function_exists('terbilang')) {
            function terbilang($angka)
            {
                $angka = abs($angka);
                $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                $terbilang = "";
                if ($angka < 12) {
                    $terbilang = " " . $baca[$angka];
                } else if ($angka < 20) {
                    $terbilang = terbilang($angka - 10) . " Belas";
                } else if ($angka < 100) {
                    $terbilang = terbilang((int) ($angka / 10)) . " Puluh" . terbilang($angka % 10);
                } else if ($angka < 200) {
                    $terbilang = " Seratus" . terbilang($angka - 100);
                } else if ($angka < 1000) {
                    $terbilang = terbilang((int) ($angka / 100)) . " Ratus" . terbilang($angka % 100);
                } else if ($angka < 2000) {
                    $terbilang = " Seribu" . terbilang($angka - 1000);
                } else if ($angka < 1000000) {
                    $terbilang = terbilang((int) ($angka / 1000)) . " Ribu" . terbilang($angka % 1000);
                } else if ($angka < 1000000000) {
                    $terbilang = terbilang((int) ($angka / 1000000)) . " Juta" . terbilang($angka % 1000000);
                } else if ($angka < 1000000000000) {
                    $terbilang = terbilang((int) ($angka / 1000000000)) . " Milyar" . terbilang($angka % 1000000000);
                }
                return $terbilang;
            }
        }
    @endphp

    <div
        class="no-print p-4 bg-white border-b shadow-md mb-6 flex justify-between items-center max-w-[22cm] mx-auto rounded-b-xl sticky top-0 z-50">
        <div>
            <h1 class="font-bold text-lg text-slate-800">Pratinjau Layout Bukti Kas</h1>
            <p class="text-sm text-slate-500">Terdapat {{ $journals->count() }} dokumen siap cetak.</p>
        </div>
        <button onclick="window.print()"
            class="px-5 py-2 bg-slate-800 text-white rounded-md font-semibold hover:bg-slate-700 transition">🖨️ Cetak
            Dokumen</button>
    </div>

    @foreach($journals as $index => $journal)
        @php
            $total = $journal->entries->filter(fn($e) => $e->is_debit)->sum('amount');
        @endphp
        <!-- VOUCHER PAGE -->
        <div
            class="page-break mb-10 w-[21.5cm] min-h-[14cm] mx-auto bg-white pt-6 pb-2 px-3 shadow-sm flex flex-col justify-center">

            <!-- OUTER BORDER 1 -->
            <div class="garis-hitam border border-black p-[2px]">
                <!-- OUTER BORDER 2 -->
                <div class="garis-hitam border-[1px] border-black p-[1px] flex flex-col h-full bg-white">

                    <!-- ROW 1 : HEADER -->
                    <div class="flex h-24">
                        <!-- LOGO & TITLE -->
                        <div
                            class="garis-hitam border-r border-black w-[35%] h-full flex flex-col items-center justify-between pt-2 pb-0 px-1">
                            <div class="flex-1 flex items-center justify-center w-full overflow-hidden">
                                <img src="{{ asset('images/logo_voucher.png') }}" class="max-h-16 w-auto object-contain">
                            </div>
                            <div class="w-full text-center font-bold text-[13px] tracking-wide mb-0">
                                PT. ANDALAS KARYA MULIA
                            </div>
                        </div>

                        <!-- TITLE RIGHT -->
                        <div class="w-[65%] flex flex-col">
                            <div class="garis-hitam h-[55%] border-b border-black flex items-center justify-center">
                                <span class="font-sans font-bold text-[17px] tracking-wide text-gray-800">VOUCHER
                                    PERSETUJUAN PEMBAYARAN</span>
                            </div>
                            <div class="h-[45%] flex text-[13px]">
                                <div class="garis-hitam w-3/5 border-r border-black flex flex-col">
                                    <div class="garis-hitam border-b border-black text-center py-[2px] text-gray-700">
                                        Tanggal Penerimaan Dokumen</div>
                                    <div class="flex-1"></div>
                                </div>
                                <div class="w-2/5 flex flex-col">
                                    <div class="garis-hitam border-b border-black text-center py-[2px] text-gray-700">Nomor
                                        Account</div>
                                    <div
                                        class="flex-1 text-center flex items-center justify-center font-mono font-bold tracking-widest text-[11pt]">
                                        <!-- {{ $journal->journal_number }} -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- LOWER BODY -->
            <div class="garis-hitam border-[1px] border-black mt-[3px]">
                <div class="p-4 flex flex-col min-h-[340px]">

                    <!-- TELAH DISETUJUI -->
                    <div class="flex flex-col text-[14px] mb-3">
                        <div class="relative h-6 border-b-[1.5px] border-black flex items-end">
                            <div class="pb-[1px]">TELAH DISETUJUI PEMBAYARAN CASH/</div>
                        </div>
                        <div class="relative h-6 border-b-[1.5px] border-black mt-1 flex items-end">
                            <div class="pb-[1px]">BANK</div>
                        </div>
                    </div>

                    <!-- SEBESAR RP & TERBILANG -->
                    <div class="flex flex-col text-[14px]">
                        <!-- SEBESAR Row 1 (WITH GAP) -->
                        <div class="relative h-6 flex items-end">
                            <div class="w-[21rem] flex items-end border-b-[1.5px] border-black">
                                <div class="w-28 pb-[1px]">SEBESAR Rp.</div>
                                <div
                                    class="flex-1 text-left font-mono font-bold text-[11pt] pb-[1px] relative top-[3px] ml-2">
                                    {{ number_format($total, 0, ',', '.') }}
                                </div>
                            </div>
                            <!-- GAP -->
                            <div class="w-4"></div>
                            <div class="flex-1 flex items-end border-b-[1.5px] border-black">
                                <div
                                    class="font-mono font-bold text-[10pt] uppercase tracking-wide pb-[1px] relative top-[3px]">
                                    ( {{ trim(terbilang($total)) }} RUPIAH )
                                </div>
                            </div>
                        </div>
                        <!-- SEBESAR Row 2 (Terbilang L2) -->
                        <div class="relative h-6 border-b-[1.5px] border-black mt-1">
                        </div>
                    </div>

                    <!-- UNTUK -->
                    <div class="flex flex-col mt-4 mb-6 text-[14px]">
                        <!-- UNTUK Row 1 -->
                        <div class="relative h-6 border-b-[1.5px] border-black flex items-end">
                            <div class="w-28 pb-[1px]">UNTUK :</div>
                            <div class="flex-1 font-mono font-bold text-[11pt] pb-[1px] relative top-[3px] ml-2">
                                {{ $journal->description ?? '—' }}
                            </div>
                        </div>
                        <!-- UNTUK Row 2 -->
                        <div class="relative h-6 border-b-[1.5px] border-black mt-1"></div>
                        <!-- UNTUK Row 3 -->
                        <div class="relative h-6 border-b-[1.5px] border-black mt-1"></div>
                        <!-- UNTUK Row 4 -->
                        <div class="relative h-6 border-b-[1.5px] border-black mt-1"></div>
                    </div>

                    <!-- BOTTOM DIBUKUKAN & TTDS -->
                    <div class="flex mt-auto mb-1">
                        <!-- TABEL DIBUKUKAN -->
                        <div class="w-48 text-[13px]">
                            <table class="w-full text-center border-collapse garis-hitam border border-black">
                                <tr>
                                    <td colspan="2" class="garis-hitam border border-black py-1">DIBUKUKAN</td>
                                </tr>
                                <tr>
                                    <td class="garis-hitam border border-black w-1/2 py-1">D</td>
                                    <td class="garis-hitam border border-black w-1/2 py-1">K</td>
                                </tr>
                                @for ($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td class="garis-hitam border border-black h-6"></td>
                                        <td class="garis-hitam border border-black h-6"></td>
                                    </tr>
                                @endfor
                            </table>
                        </div>

                        <!-- SPACE & TTD -->
                        <div class="flex-1 ml-6 flex flex-col text-[13px]">
                            <div class="text-right tracking-wide mr-4">
                                PEKANBARU, <span
                                    class="font-mono font-bold text-[11pt] border-b border-dotted border-gray-400 pb-0 inline-block min-w-[160px] text-left pl-2 relative top-[3px]">{{ \Carbon\Carbon::parse($journal->date)->format('d-M-Y') }}</span>
                            </div>
                            <div class="flex justify-end gap-8 items-end h-full text-center pb-2 px-4 mt-6">
                                <div class="flex flex-col items-center w-32">
                                    <div>YANG MENYETUJUI</div>
                                    <div class="mt-16 w-full flex">
                                        <span>(</span>
                                        <div class="garis-hitam flex-1 border-b-[1.5px] border-black mx-1"></div>
                                        <span>)</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center w-32">
                                    <div>YANG MEMBAYAR</div>
                                    <div class="mt-16 w-full flex">
                                        <span>(</span>
                                        <div class="garis-hitam flex-1 border-b-[1.5px] border-black mx-1"></div>
                                        <span>)</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center w-32">
                                    <div>YANG MENERIMA</div>
                                    <div class="mt-16 w-full flex">
                                        <span>(</span>
                                        <div class="garis-hitam flex-1 border-b-[1.5px] border-black mx-1"></div>
                                        <span>)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    @endforeach

    @if($journals->count() > 0)
        <script>
            window.onload = function () {
                setTimeout(function () {
                    window.print();
                }, 600);
            }
        </script>
    @endif
</body>

</html>