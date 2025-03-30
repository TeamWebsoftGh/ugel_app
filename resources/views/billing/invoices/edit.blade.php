<form class="needs-validation" action="{{ route('invoices.store') }}" id="invoice">
    @csrf
    <input type="hidden" id="_id" name="id" value="{{ $item->id }}">
    <input type="hidden" id="_name" name="me" value="{{ $item->name }}">
    <div class="card-body border-bottom border-bottom-dashed p-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="profile-user mx-auto  mb-3">
                    <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                    <label for="profile-img-file-input" class="d-block" tabindex="0">
                        <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="height: 60px; width: 256px;">
                            <img src="{{asset(settings("logo"))}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo">
                            <img src="{{asset(settings("logo"))}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo">
                        </span>
                    </label>
                </div>
                <div>
                    <div>
                        <label for="companyAddress">Address</label>
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control bg-light border-0" id="companyAddress" rows="3" placeholder="Company Address" required=""></textarea>
                        <div class="invalid-feedback">
                            Please enter a address
                        </div>
                    </div>
                    <div>
                        <input type="text" class="form-control bg-light border-0" id="companyaddpostalcode" minlength="5" maxlength="6" placeholder="Enter Postal Code" required="">
                        <div class="invalid-feedback">
                            The US zip code must contain 5 digits, Ex. 45678
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-4 ms-auto">
                <div class="mb-2">
                    <input type="text" class="form-control bg-light border-0" id="registrationNumber" maxlength="12" placeholder="Legal Registration No" required="">
                    <div class="invalid-feedback">
                        Please enter a registration no, Ex., 012345678912
                    </div>
                </div>
                <div class="mb-2">
                    <input type="email" class="form-control bg-light border-0" id="companyEmail" placeholder="Email Address" required="">
                    <div class="invalid-feedback">
                        Please enter a valid email, Ex., example@gamil.com
                    </div>
                </div>
                <div class="mb-2">
                    <input type="text" class="form-control bg-light border-0" id="companyWebsite" placeholder="Website" required="">
                    <div class="invalid-feedback">
                        Please enter a website, Ex., www.example.com
                    </div>
                </div>
                <div>
                    <input type="text" class="form-control bg-light border-0" data-plugin="cleave-phone" id="compnayContactno" placeholder="Contact No" required="">
                    <div class="invalid-feedback">
                        Please enter a contact number
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <x-form.input-field
                name="invoice_number_"
                label="Invoice No"
                type="text"
                placeholder="Name"
                :value="$item->invoice_number"
                readonly
                class="col-lg-3 col-sm-6"
            />

            <x-form.input-field
                name="invoice_date_"
                label="Invoice Date"
                type="text"
                :value="$item->invoice_date"
                disabled
                class="col-lg-3 col-sm-6"
            />
            <x-form.input-field
                name="status_"
                label="Status"
                type="text"
                :value="$item->status"
                disabled
                class="col-lg-3 col-sm-6"
            />
            <x-form.input-field
                name="total_amount_"
                label="Total Amount"
                type="text"
                :value="$item->total_amount"
                disabled
                class="col-lg-3 col-sm-6"
            />
        </div>
        <!--end row-->
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="invoice-table table table-borderless table-nowrap mb-0">
                <thead class="align-middle">
                <tr class="table-active">
                    <th scope="col" style="width: 50px;">#</th>
                    <th scope="col">
                        Product Details
                    </th>
                    <th scope="col" style="width: 120px;">
                        <div class="d-flex currency-select input-light align-items-center">
                            Rate
                            <div class="choices" data-type="select-one" tabindex="0" role="listbox" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-selectborder-0 bg-light choices__input" data-choices="" data-choices-search-false="" id="choices-payment-currency" onchange="otherPayment()" hidden="" tabindex="-1" data-choice="active">
                                        <option value="$" selected="">($)</option>
                                        <option value="£">(£)</option>
                                        <option value="₹">(₹)</option>
                                        <option value="€">(€)</option>
                                    </select><div class="choices__list choices__list--single"><div class="choices__item choices__item--selectable" data-item="" data-id="1" data-value="$" aria-selected="true" role="option">($)</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><div class="choices__list" role="listbox"><div id="choices--choices-payment-currency-item-choice-1" class="choices__item choices__item--choice is-selected choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="$" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">($)</div><div id="choices--choices-payment-currency-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="£" data-select-text="Press to select" data-choice-selectable="">(£)</div><div id="choices--choices-payment-currency-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="€" data-select-text="Press to select" data-choice-selectable="">(€)</div><div id="choices--choices-payment-currency-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="₹" data-select-text="Press to select" data-choice-selectable="">(₹)</div></div></div></div>
                        </div>
                    </th>
                    <th scope="col" style="width: 120px;">Quantity</th>
                    <th scope="col" class="text-end" style="width: 150px;">Amount</th>
                    <th scope="col" class="text-end" style="width: 105px;"></th>
                </tr>
                </thead>
                <tbody id="newlink">
                <tr id="1" class="product">
                    <th scope="row" class="product-id">1</th>
                    <td class="text-start">
                        <div class="mb-2">
                            <input type="text" class="form-control bg-light border-0" id="productName-1" placeholder="Product Name" required="">
                            <div class="invalid-feedback">
                                Please enter a product name
                            </div>
                        </div>
                        <textarea class="form-control bg-light border-0" id="productDetails-1" rows="2" placeholder="Product Details"></textarea>
                    </td>
                    <td>
                        <input type="number" class="form-control product-price bg-light border-0" id="productRate-1" step="0.01" placeholder="0.00" required="">
                        <div class="invalid-feedback">
                            Please enter a rate
                        </div>
                    </td>
                    <td>
                        <div class="input-step">
                            <button type="button" class="minus">–</button>
                            <input type="number" class="product-quantity" id="product-qty-1" value="0" readonly="">
                            <button type="button" class="plus">+</button>
                        </div>
                    </td>
                    <td class="text-end">
                        <div>
                            <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-1" placeholder="$0.00" readonly="">
                        </div>
                    </td>
                    <td class="product-removal">
                        <a href="javascript:void(0)" class="btn btn-success">Delete</a>
                    </td>
                </tr>
                </tbody>
                <tbody>
                <tr id="newForm" style="display: none;"><td class="d-none" colspan="5"><p>Add New Form</p></td></tr>
                <tr>
                    <td colspan="5">
                        <a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary fw-medium"><i class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                    </td>
                </tr>
                <tr class="border-top border-top-dashed mt-2">
                    <td colspan="3"></td>
                    <td colspan="2" class="p-0">
                        <table class="table table-borderless table-sm table-nowrap align-middle mb-0">
                            <tbody>
                            <tr>
                                <th scope="row">Sub Total</th>
                                <td style="width:150px;">
                                    <input type="text" class="form-control bg-light border-0" id="cart-subtotal" value="{{format_money($item->sub_total_amount)}}" readonly="">
                                </td>
                            </tr>

                            <tr class="border-top border-top-dashed">
                                <th scope="row">Total Amount</th>
                                <td>
                                    <input type="text" class="form-control bg-light border-0" id="cart-total" placeholder="{{format_money($item->total_amount)}}" readonly="">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!--end table-->
                    </td>
                </tr>
                </tbody>
            </table>
            <!--end table-->
        </div>
        <div class="row mt-3">
            <div class="col-lg-4">
                <div class="mb-2">
                    <label for="choices-payment-type" class="form-label text-muted text-uppercase fw-semibold">Payment Details</label>
                    <div class="input-light">
                        <div class="choices" data-type="select-one" tabindex="0" role="listbox" aria-label="PAYMENT DETAILS" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control bg-light border-0 choices__input" data-choices="" data-choices-search-false="" data-choices-removeitem="" id="choices-payment-type" hidden="" tabindex="-1" data-choice="active">
                                    <option value="" selected="">Payment Method</option>
                                    <option value="Mastercard">Mastercard</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Visa">Visa</option>
                                    <option value="Paypal">Paypal</option>
                                </select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" aria-selected="true" role="option" data-placeholder="" data-deletable="">Payment Method<button type="button" class="choices__button" aria-label="Remove item: " data-button="">Remove item</button></div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><div class="choices__list" role="listbox"><div id="choices--choices-payment-type-item-choice-1" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Payment Method</div><div id="choices--choices-payment-type-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Credit Card" data-select-text="Press to select" data-choice-selectable="">Credit Card</div><div id="choices--choices-payment-type-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Mastercard" data-select-text="Press to select" data-choice-selectable="">Mastercard</div><div id="choices--choices-payment-type-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="Paypal" data-select-text="Press to select" data-choice-selectable="">Paypal</div><div id="choices--choices-payment-type-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Visa" data-select-text="Press to select" data-choice-selectable="">Visa</div></div></div></div>
                    </div>
                </div>
                <div class="mb-2">
                    <input class="form-control bg-light border-0" type="text" id="cardholderName" placeholder="Card Holder Name">
                </div>
                <div class="mb-2">
                    <input class="form-control bg-light border-0" type="text" id="cardNumber" placeholder="xxxx xxxx xxxx xxxx">
                </div>
                <div>
                    <input class="form-control  bg-light border-0" type="text" id="amountTotalPay" placeholder="$0.00" readonly="">
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
        <div class="mt-4">
            <label for="exampleFormControlTextarea1" class="form-label text-muted text-uppercase fw-semibold">NOTES</label>
            <textarea class="form-control alert alert-info" id="exampleFormControlTextarea1" placeholder="Notes" rows="2" required="">All accounts are to be paid within 7 days from receipt of invoice. To be paid by cheque or credit card or direct payment online. If account is not paid within 7 days the credits details supplied as confirmation of work undertaken will be charged the agreed quoted fee noted above.</textarea>
        </div>
        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
            <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
            <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download Invoice</a>
            <a href="javascript:void(0);" class="btn btn-danger"><i class="ri-send-plane-fill align-bottom me-1"></i> Send Invoice</a>
        </div>
    </div>
</form>

<!-- jQuery Handling -->
<!--Invoice create init js-->
<script src="/assets/js/pages/invoicecreate.init.js"></script>
<script>
    $(document).ready(function() {
        // Update price inputs and rent type selects on page load
        updateRentTypesAndPrices();

        // Event listener for when type changes
        $('#type').change(function() {
            updateRentTypesAndPrices();
        });

        function updateRentTypesAndPrices() {
            var type = $('#type').val();

            $('.rent-type').each(function() {
                if ($(this).find('option:selected').val() === '') { // Only update if no value is selected
                    if (type === 'vacation') {
                        $(this).html(`
                        <option value="daily" selected>Daily</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem">Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    }
                    else if (type === 'student') {
                        $(this).html(`
                        <option value="daily">Daily</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem" selected>Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    } else {
                        $(this).html(`
                        <option value="daily">Daily</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="per-sem">Per-Sem</option>
                        <option value="custom">Custom</option>
                    `);
                    }
                }
            });

            $('.price-input').each(function() {
                if (type === 'vacation') {
                    // Always update price for vacation type
                    $(this).val($(this).data('general-amount'));
                }
                else if (!$(this).val()) {
                    // Only update if empty for other types
                    $(this).val($(this).data('rent-amount'));
                }
            });
        }
    });
</script>
