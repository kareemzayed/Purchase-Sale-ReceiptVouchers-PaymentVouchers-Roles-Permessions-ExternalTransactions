@extends('backend.auth.master')
@section('content')
    <div id="app">
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card card-primary login">
                            <div class="card-header">
                                <h4 class="text-white">التحقق باستخدام الرمز</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.password.verify.code') }}" method="POST"
                                    class="cmn-form mt-30">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-white">رمز التحقق</label>
                                        <input type="text" name="code" id="code" class="form-control">
                                    </div>
                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <a href="{{ route('admin.password.reset') }}" class="text-white text--small">حاول
                                            إرسال مرة أخرى</a>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="login-button text-white" tabindex="4">
                                            التحقق من الرمز
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer text-white">
                            @if ($general->copyright)
                                {{ $general->copyright }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('script')
    <script>
        (function($) {
            "use strict";
            $('#code').on('input change', function() {
                var xx = document.getElementById('code').value;
                $(this).val(function(index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });
            });
        })(jQuery)
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