@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <div class="section">
            <div class="section-header pl-0 pb-3">
                <h3 class="pl-0">قائمة المعاملات بعملات متعددة</h3>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>
                        @if (checkPermission(70))
                            <button class="btn btn-primary add"><i class="fa fa-plus"></i>
                                إنشاء معاملة جديدة</button>
                        @endif
                    </h4>
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('admin.trans.with.currency.search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="رقم المعاملة" name="utr"
                                    value="{{ isset($search['utr']) ? $search['utr'] : '' }}">
                                <select class="form-control" name="trans_type">
                                    <option disabled selected>نوع المعاملة</option>
                                    <option value="">عرض الكل</option>
                                    <option value="1"
                                        {{ isset($search['trans_type']) && $search['trans_type'] == '1' ? 'selected' : '' }}>
                                        شراء</option>
                                    <option value="2"
                                        {{ isset($search['trans_type']) && $search['trans_type'] == '2' ? 'selected' : '' }}>
                                        بيع</option>
                                </select>
                                <select class="form-control" name="currency_id">
                                    <option disabled selected>عملة المعاملة</option>
                                    @forelse ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ isset($search['currency_id']) && $search['currency_id'] == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name . ' - ' . $currency->code }}</option>
                                    @empty
                                        <option disabled selected>لم يتم العثور علي اي عملات</option>
                                    @endforelse
                                </select>
                                <input type="date" class="form-control" placeholder="تاريخ المعاملة" name="date"
                                    value="{{ isset($search['date']) ? $search['date'] : '' }}">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نوع المعاملة</th>
                                    <th>عملة المعاملة</th>
                                    <th>المبلغ بعملة المعاملة</th>
                                    <th>المبلغ بالعملة الاساسية</th>
                                    <th>صافي الربح</th>
                                    <th>ملاحظة</th>
                                    <th>رقم المعاملة</th>
                                    <th>في</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $key => $value)
                                    <tr>
                                        <td>{{ $key + $transactions->firstItem() }}</td>
                                        <td>
                                            @if ($value->trans_type == 1)
                                                <span class="text-success">معاملة شراء</span>
                                            @elseif($value->trans_type == 2)
                                                <span class="text-danger">معاملة بيع</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($value->currency)->name . ' (' . optional($value->currency)->code . ') ' }}
                                        </td>
                                        <td>
                                            {{ number_format($value->amount_in_other_currency, 2) . ' ' . optional($value->currency)->code }}
                                        </td>
                                        <td>
                                            {{ number_format($value->amount_in_base_currency, 2) . ' ' . $general->site_currency }}
                                        </td>
                                        <td>
                                            {{ number_format($value->charge, 2) . ' ' . $general->site_currency }}
                                        </td>
                                        <td>{{ $value->note ?? 'N/A' }}</td>
                                        <td>{{ $value->utr }}</td>
                                        <td>
                                            {{ $value->created_at->format('m/d/Y h:i A') }}
                                        </td>
                                        <td style="width:12%">
                                            @if (checkPermission(71))
                                                <button
                                                    data-href="{{ route('admin.trans.with.currency.update', $value->id) }}"
                                                    data-trans="{{ $value }}" class="btn btn-md btn-info update"><i
                                                        class="fa fa-pen"></i></button>
                                            @endif
                                            @if (checkPermission(72))
                                                <button
                                                    data-href="{{ route('admin.trans.with.currency.delete', $value->id) }}"
                                                    data-trans="{{ $value }}"
                                                    class="btn btn-md btn-danger delete"><i
                                                        class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">لم يتم العثور علي اي معاملات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($transactions->hasPages())
                        <div class="card-footer">
                            {{ $transactions->links('backend.partial.paginate') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Model -->
    <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST">
                @csrf
                {{ method_field('DELETE') }}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row col-md-12">
                            <p>هل أنت متأكد أنك تريد حذف هذه المعاملة؟ سيؤدي حذفها إلى استرجاع الأموال وإلغاء المعاملة بشكل
                                كامل.
                            </p>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-danger" type="submit">حذف</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!------------------>

    <!-- Create And Update Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" method="post">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label for="trans_type">نوع المعاملة <span class="text-danger">*</span> </label>
                                <select class="form-control" name="trans_type" id="trans_type">
                                    <option disabled selected value="0">نوع المعاملة</option>
                                    <option value="1">شراء</option>
                                    <option value="2">بيع</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="currency_id">عملة المعاملة <span class="text-danger">*</span> </label>
                                <select class="form-control" name="currency_id" id="currency_id">
                                    <option disabled selected value="0">عملة المعاملة</option>
                                    @forelse ($currencies as $currency)
                                        <option value="{{ $currency->id }}" data-code="{{ $currency->code }}"
                                            data-name="{{ $currency->name }}">
                                            {{ $currency->name . ' - ' . $currency->code }}</option>
                                    @empty
                                        <option disabled selected>لم يتم العثور علي اي عملات</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="amount_in_other_currency">مبلغ المعاملة بالعملة (<span
                                        class="currency_name"></span>) <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input type="number" name="amount_in_other_currency" id="amount_in_other_currency"
                                        placeholder="0.00" class="form-control" step="any" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text currency_code">

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="amount_in_base_currency">مبلغ المعاملة بالعملة الأساسية <span
                                        class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input type="number" name="amount_in_base_currency" id="amount_in_base_currency"
                                        placeholder="0.00" class="form-control" step="any" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $general->site_currency }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="charge_type">نوع الارباح <span class="text-danger">*</span> </label>
                                <select class="form-control" name="charge_type" id="charge_type">
                                    <option disabled selected value="0">اختر نوع الرسوم</option>
                                    <option value="1">ثابتة</option>
                                    <option value="2">نسبية</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="charge_value">قيمة الأرباح <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input type="number" name="charge_value" id="charge_value" placeholder="0.00"
                                        class="form-control" step="any" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text charge_value">

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-12">
                                <label for="charge">صافي الربح من هذه المعاملة (للتوضيح فقط) <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="charge" id="charge" placeholder="0.00"
                                        class="form-control" step="any" required disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $general->site_currency }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-12">
                                <label for="note">ملاحظة</label>
                                <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        <button type="submit" id="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            'use strict'

            $('.add').on('click', function() {
                const modal = $('#modelId');
                modal.find('.modal-title').text("إنشاء معاملة جديدة")
                modal.find('select[name=trans_type]').val('0')
                modal.find('select[name=currency_id]').val('0')
                modal.find('input[name=amount_in_other_currency]').val('')
                modal.find('input[name=amount_in_base_currency]').val('')
                modal.find('select[name=charge_type]').val('0')
                modal.find('input[name=charge_value]').val('')
                modal.find('input[name=charge]').val('')
                modal.find('textarea[name=note]').val('')
                $('.currency_code').text('');
                $('.currency_name').text('');
                $('.charge_value').text('');
                modal.find('form').attr('action', '{{ route('admin.trans.with.currency.create') }}');
                modal.modal('show');
            })

            $('.update').on('click', function() {
                const modal = $('#modelId');
                const form = modal.find('form');
                form[0].reset();
                modal.find('.modal-title').text("تعديل المعاملة رقم" + ' ' + ($(this)
                    .data('trans').utr || ''));
                modal.find('select[name=trans_type]').val($(this).data('trans').trans_type)
                modal.find('select[name=currency_id]').val($(this).data('trans').currency_id)
                modal.find('input[name=amount_in_other_currency]').val($(this).data('trans')
                    .amount_in_other_currency)
                modal.find('input[name=amount_in_base_currency]').val($(this).data('trans')
                    .amount_in_base_currency)
                modal.find('select[name=charge_type]').val($(this).data('trans')
                    .charge_type)
                updateChargeValueText($(this).data('trans').charge_type)
                modal.find('input[name=charge_value]').val(
                    $(this).data('trans').charge_type == 1 ? formatDecimalAmount($(this).data('trans')
                        .charge_value, 2) : $(this).data('trans').charge_value
                );
                modal.find('input[name=charge]').val(formatDecimalAmount($(this).data('trans')
                    .charge, 2))
                modal.find('textarea[name=note]').text($(this).data('trans').note)
                $('.currency_code').text($(this).data('trans').currency.code);
                $('.currency_name').text($(this).data('trans').currency.name);
                modal.find('form').attr('action', $(this).data('href'));
                modal.modal('show');
            })

            $('.delete').on('click', function() {
                const modal = $('#delete_modal')
                modal.find('.modal-title').text("حذف المعاملة رقم" + ' ' + ($(this)
                    .data('trans').utr || ''));
                modal.find('form').attr('action', $(this).data('href'))
                modal.modal('show');
            })
        });

        function updateChargeValueText(chargeType) {
            var chargeValueText = '';
            if (chargeType == 1) {
                chargeValueText = '{{ $general->site_currency }}';
            } else if (chargeType == 2) {
                chargeValueText = '%';
            }
            $('.charge_value').text(chargeValueText);
        }

        $('#charge_type').on('change', function() {
            updateChargeValueText($(this).val());
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chargeTypeSelect = document.getElementById('charge_type');
            const chargeValueInput = document.getElementById('charge_value');
            const chargeResultInput = document.getElementById('charge');
            const amountInBaseCurrencyInput = document.getElementById('amount_in_base_currency');

            function updateChargeResult() {
                const chargeType = parseInt(chargeTypeSelect.value);
                const chargeValue = parseFloat(chargeValueInput.value) || 0;
                const baseAmount = parseFloat(amountInBaseCurrencyInput.value) || 0;

                if (chargeType === 1) {
                    chargeResultInput.value = chargeValue.toFixed(2);
                } else if (chargeType === 2) {
                    const chargeAmount = (baseAmount * chargeValue) / 100;
                    chargeResultInput.value = chargeAmount.toFixed(2);
                }
            }
            chargeTypeSelect.addEventListener('change', updateChargeResult);
            chargeValueInput.addEventListener('input', updateChargeResult);
            amountInBaseCurrencyInput.addEventListener('input', updateChargeResult);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#currency_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var currencyCode = selectedOption.data('code');
                var currencyName = selectedOption.data('name');
                $('.currency_code').text(currencyCode);
                $('.currency_name').text(currencyName);
            });
            $('#currency_id').trigger('change');
        });
    </script>
    <script>
        $(function() {
            'use strict';

            $('form').on('submit', function() {
                const clickedButton = $(document.activeElement);
                if (clickedButton.is(':submit')) {
                    clickedButton.prop('disabled', true).html(
                        'جاري ... <i class="fa fa-spinner fa-spin"></i>');
                    $(':submit', this).not(clickedButton).prop('disabled', true);
                }
            });
        });
    </script>
@endpush
