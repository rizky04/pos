<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header py-2">
                <h6 class="modal-title">Struk Pembayaran</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="printArea">
                <div class="receipt">
                    <h6 class="text-center fw-bold">TOKO KAMU</h6>
                    <div class="text-center small mb-2">
                        Jl. Contoh No 123<br>
                        Telp: 0812-3456-7890
                    </div>

                    <div class="d-flex justify-content-between small">
                        <span>Tanggal</span>
                        <span id="strukDate"></span>
                    </div>

                    <hr>

                    <div id="strukItems" class="small"></div>

                    <hr>

                    <div class="d-flex justify-content-between small">
                        <span>Subtotal</span>
                        <span id="strukSub"></span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Diskon</span>
                        <span id="strukDisc"></span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>PPN</span>
                        <span id="strukPpn"></span>
                    </div>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="strukTotal"></span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between small">
                        <span>Bayar</span>
                        <span id="strukPay"></span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Kembalian</span>
                        <span id="strukChange"></span>
                    </div>

                    <hr>

                    <div class="text-center small">Terima kasih!</div>
                </div>
            </div>

            <div class="modal-footer p-2">
                <button class="btn btn-dark w-100" onclick="printStruk58()">Print 58mm</button>
            </div>

        </div>
    </div>
</div>
<style>
    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            width: 58mm;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
    }
    </style>
