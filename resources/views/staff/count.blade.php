@extends('layouts.staff')

@section('content')

<h3>Counting – {{ $auditFile->file_name }}</h3>

<div class="card">
    <label><b>Scan QR / Type Barcode</b></label>

    <div style="display:flex;gap:10px;margin-top:10px;">
        <input
            type="text"
            id="barcodeInput"
            placeholder="Enter barcode"
            style="flex:1;padding:14px;font-size:18px;"
            autofocus
        >

        <button
            type="button"
            onclick="searchBarcode()"
            style="padding:14px 18px;font-size:18px;background:#0f766e;color:#fff;border:none;border-radius:6px;"
        >
            🔍
        </button>

        <button
            type="button"
            onclick="startScanner()"
            style="padding:14px 18px;font-size:18px;background:#555;color:#fff;border:none;border-radius:6px;"
        >
            📷
        </button>
    </div>

    <div id="errorMsg" style="color:red;margin-top:8px;display:none;font-weight:600;"></div>
</div>

{{-- QR CAMERA --}}
<div id="scannerBox" style="display:none;margin-top:10px;">
    <div id="qr-reader" style="width:100%;"></div>
</div>

{{-- ITEM DETAILS --}}
<div id="itemBox" class="card" style="display:none;margin-top:12px;">
    <div
        id="itemName"
        style="font-size:22px;font-weight:700;margin-bottom:10px;"
    ></div>

    <div style="font-size:17px;line-height:1.7;">
        <div><b>Brand:</b> <span id="brand"></span></div>
        <div><b>Size:</b> <span id="size"></span></div>
    </div>

    {{-- SYSTEM QTY HIDDEN --}}
    <span id="systemQty" style="display:none;"></span>

    <hr>

    <input
        type="number"
        id="qtyInput"
        placeholder="Enter quantity"
        style="width:100%;padding:14px;font-size:18px;"
    >

    <button
        id="submitBtn"
        type="button"
        onclick="submitQty()"
        style="margin-top:12px;width:100%;padding:14px;font-size:18px;background:green;color:#fff;border:none;border-radius:6px;"
    >
        ✅ Submit
    </button>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
console.log('JS LOADED ✅');

let currentStockId = null;
let alreadySubmitted = false;
let qrScanner = null;

/* SEARCH BARCODE */
function searchBarcode() {
    const code = document.getElementById('barcodeInput').value.trim();
    const errorMsg = document.getElementById('errorMsg');

    if (!code) {
        errorMsg.innerText = 'Please enter barcode';
        errorMsg.style.display = 'block';
        return;
    }

    fetch('/staff/barcode/' + encodeURIComponent(code))
        .then(res => {
            if (!res.ok) throw 'Not found';
            return res.json();
        })
        .then(data => {

            currentStockId = data.item.id;

            document.getElementById('itemBox').style.display = 'block';
            document.getElementById('itemName').innerText = data.item.name;
            document.getElementById('brand').innerText = data.item.brand ?? '-';
            document.getElementById('size').innerText = data.item.size ?? '-';

            document.getElementById('systemQty').innerText = data.item.system_qty;

            // 🔒 RELIABLE LOCK (SERVER-TRUSTED)
            if (data.item.is_counted === true) {
                alreadySubmitted = true;
                document.getElementById('qtyInput').disabled = true;
                document.getElementById('submitBtn').disabled = true;
                errorMsg.innerText = '⚠ Already counted';
                errorMsg.style.display = 'block';
            } else {
                alreadySubmitted = false;
                document.getElementById('qtyInput').disabled = false;
                document.getElementById('submitBtn').disabled = false;
                errorMsg.style.display = 'none';
                document.getElementById('qtyInput').focus();
            }
        })
        .catch(() => {
            document.getElementById('itemBox').style.display = 'none';
            errorMsg.innerText = '❌ Barcode not found';
            errorMsg.style.display = 'block';
        });
}

/* SUBMIT */
function submitQty() {
    if (alreadySubmitted) {
        alert('Already submitted');
        return;
    }

    const qty = document.getElementById('qtyInput').value;
    if (qty === '') {
        alert('Enter quantity');
        return;
    }

    fetch("{{ route('staff.count.store', $auditFile->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            stock_id: currentStockId,
            entered_quantity: qty
        })
    })
    .then(res => res.json())
    .then(resp => {
    if (!resp.success) {
        alert(resp.message || 'Error');
        return;
    }

    alert('✅ Saved');

    // ✅ FULL RESET FOR NEXT ITEM
    alreadySubmitted = false;
    currentStockId = null;

    document.getElementById('qtyInput').value = '';
    document.getElementById('qtyInput').disabled = false;
    document.getElementById('submitBtn').disabled = false;

    document.getElementById('itemBox').style.display = 'none'; // ⭐ FIX
    document.getElementById('errorMsg').style.display = 'none';

    document.getElementById('barcodeInput').value = '';
    document.getElementById('barcodeInput').focus();
});
}

/* QR CAMERA (MOBILE SAFE) */
function startScanner() {
    const box = document.getElementById('scannerBox');
    box.style.display = 'block';

    if (!qrScanner) {
        qrScanner = new Html5Qrcode("qr-reader");
    }

    qrScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        (decodedText) => {
            qrScanner.stop();
            box.style.display = 'none';
            document.getElementById('barcodeInput').value = decodedText;
            searchBarcode();
        },
        () => {}
    );
}

/* ENTER KEY SUPPORT */
document.getElementById('barcodeInput').addEventListener('keydown', function(e){
    if (e.key === 'Enter') {
        e.preventDefault();
        searchBarcode();
    }
});
</script>
@endsection