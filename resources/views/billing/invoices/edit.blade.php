<form class="needs-validation" action="{{ route('invoices.store') }}" id="invoice" method="POST">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">

    <div class="card-body border-bottom border-bottom-dashed p-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="profile-user mx-auto mb-3">
                    <label for="profile-img-file-input" class="d-block" tabindex="0">
                        <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="height: 60px; width: 256px;">
                            <img src="{{ asset(settings("logo")) }}" class="user-profile-image img-fluid" alt="logo">
                        </span>
                    </label>
                </div>
                <div class="mt-sm-5 mt-4">
                    <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                    <p class="text-muted mb-1">{{ settings("company_address") }}</p>
                    <p class="text-muted mb-0">Zip-code: 90201</p>
                </div>
            </div>

            <div class="col-lg-4 ms-auto">
                <div class="flex-shrink-0 mt-sm-0 mt-3">
                    <h6><span class="text-muted fw-normal">Company:</span> {{ settings("company_name") }}</h6>
                    <h6><span class="text-muted fw-normal">Email:</span> {{ settings("company_email") }}</h6>
                    <h6><span class="text-muted fw-normal">Website:</span> <a href="{{ url("/") }}" class="link-primary" target="_blank">{{ url("/") }}</a></h6>
                    <h6><span class="text-muted fw-normal">Contact No:</span> {{ settings("company_phone_number") }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row g-3">
            <x-form.input-field name="invoice_number_" label="Invoice No" type="text" :value="$item->invoice_number" readonly class="col-lg-3 col-sm-6" />
            <x-form.input-field name="invoice_date_" label="Invoice Date" type="text" :value="$item->invoice_date" disabled class="col-lg-3 col-sm-6" />
            <x-form.input-field name="status_" label="Status" type="text" :value="$item->status" disabled class="col-lg-3 col-sm-6" />
            <x-form.input-field name="total_amount_" label="Total Amount" type="text" :value="$item->total_amount" disabled class="col-lg-3 col-sm-6" />
        </div>
    </div>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="invoice-table table table-borderless table-nowrap mb-0">
                <thead class="align-middle">
                <tr class="table-active">
                    <th>#</th>
                    <th>Item Details</th>
                    <th style="width: 120px;">Rate (GHS)</th>
                    <th style="width: 120px;">Quantity</th>
                    <th class="text-end" style="width: 150px;">Amount</th>
                    <th class="text-end" style="width: 105px;"></th>
                </tr>
                </thead>
                <tbody id="invoice-items-body">
                <tr>
                    <td>1</td>
                    <td>
                        <strong>{{ $item->booking->property->property_name }} Booking Fee</strong><br>
                        <small>Booking Ref: {{ $item->booking->booking_number }}</small>
                    </td>
                    <td><input type="number" class="form-control bg-light border-0" value="{{ $item->booking->total_price }}" readonly></td>
                    <td><input type="number" class="form-control bg-light border-0" value="1" readonly></td>
                    <td class="text-end"><input type="text" class="form-control bg-light border-0" value="{{ format_money($item->booking->total_price) }}" readonly></td>
                    <td></td>
                </tr>
                </tbody>
                <tbody id="dynamic-invoice-items">
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <button type="button" class="btn btn-soft-secondary fw-medium" onclick="addInvoiceItem()">
                            <i class="ri-add-fill me-1 align-bottom"></i> Add Item
                        </button>
                    </td>
                </tr>
                <tr class="border-top border-top-dashed mt-2">
                    <td colspan="3"></td>
                    <td colspan="2" class="p-0">
                        <table class="table table-borderless table-sm table-nowrap align-middle mb-0">
                            <tbody>
                            <tr>
                                <th>Booking Total</th>
                                <td><input type="text" class="form-control bg-light border-0" id="cart-subtotal" value="{{ format_money($item->sub_total_amount) }}" readonly></td>
                            </tr>
                            <tr class="border-top border-top-dashed">
                                <th>Total Amount</th>
                                <td><input type="text" class="form-control bg-light border-0" id="cart-total" value="{{ format_money($item->total_amount) }}" readonly></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4">
            <label for="exampleFormControlTextarea1" class="form-label text-muted text-uppercase fw-semibold">NOTES</label>
            <textarea class="form-control alert alert-info" id="exampleFormControlTextarea1" placeholder="Notes" rows="2" required>
All accounts are to be paid within 7 days from receipt of invoice. To be paid by cheque, credit card, or direct payment online.
            </textarea>
        </div>

        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
            @include("shared.save-button")
            <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download Invoice</a>
            <a href="javascript:void(0);" class="btn btn-danger"><i class="ri-send-plane-fill align-bottom me-1"></i> Send Invoice</a>
        </div>
    </div>
</form>

<script>
    const invoiceItems = @json($invoiceItemLookups);
    const existingItems = @json($item->items);

    $(document).ready(function () {
        existingItems.forEach(function (item, index) {
            const rate = item.quantity > 0 ? item.amount / item.quantity : 0;
            item.rate = rate.toFixed(2);
            item.lookup_id = item.invoice_item_lookup_id;
            addInvoiceItem(item, index + 2);
        });
        recalculateTotals();
    });

    function addInvoiceItem(existing = null, rowCount = null) {
        const tableBody = $('#dynamic-invoice-items');
        if (!rowCount) rowCount = tableBody.children().length + 2;

        const row = `
            <tr>
                <td>${rowCount}</td>
                <td>
                    <select name="items[${rowCount}][lookup_id]" class="form-control" onchange="updateItemPrice(this, ${rowCount})">
                        <option value="">Select Item</option>
                        ${invoiceItems.map(opt => `<option value="${opt.id}" data-price="${opt.price}" ${existing && existing.lookup_id == opt.id ? 'selected' : ''}>${opt.name}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" name="items[${rowCount}][rate]" id="rate-${rowCount}" class="form-control" step="0.01" value="${existing ? existing.rate : 0}" oninput="updateTotal(${rowCount})"></td>
                <td><input type="number" name="items[${rowCount}][quantity]" id="qty-${rowCount}" class="form-control" value="${existing ? existing.quantity : 1}" oninput="updateTotal(${rowCount})"></td>
                <td class="text-end"><input type="number" name="items[${rowCount}][amount]" id="amount-${rowCount}" class="form-control" step="0.01" value="${existing ? parseFloat(existing.amount).toFixed(2) : 0}" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove(); recalculateTotals();">Remove</button></td>
            </tr>
        `;
        tableBody.append(row);
    }

    function updateItemPrice(select, rowId) {
        const price = parseFloat($(select).find('option:selected').data('price')) || 0;
        $(`#rate-${rowId}`).val(price);
        $(`#qty-${rowId}`).val(1);
        updateTotal(rowId);
    }

    function updateTotal(rowId) {
        const rate = parseFloat($(`#rate-${rowId}`).val()) || 0;
        const quantity = parseInt($(`#qty-${rowId}`).val()) || 0;
        const amount = rate * quantity;
        $(`#amount-${rowId}`).val(amount.toFixed(2));
        recalculateTotals();
    }

    function recalculateTotals() {
        let subtotal = 0;

        const bookingAmount = parseFloat($('#invoice-items-body input[readonly]').first().val()) || 0;
        subtotal += bookingAmount;

        $('#dynamic-invoice-items input[id^="amount-"]').each(function () {
            subtotal += parseFloat($(this).val()) || 0;
        });

        $('#cart-subtotal').val(subtotal.toFixed(2));
        $('#cart-total').val(subtotal.toFixed(2));
    }
</script>
