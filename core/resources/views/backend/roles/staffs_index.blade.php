@extends('backend.layout.master')
@section('content')
    <div class="main-content">
            <div class="section">
                <div class="section-header pl-0 pb-3">
                    <h3 class="pl-0">قائمة فريق العمل</h3>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <button class="btn btn-primary add"><i class="fa fa-plus"></i>
                                إنشاء فريق عمل جديد</button>
                        </h4>
                        <div class="card-header-form">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="اسم فريق العمل" name="username"
                                        value="{{ isset($search['username']) ? $search['username'] : '' }}">
                                    <input type="text" class="form-control" placeholder="البريد الألكتروني" name="email"
                                        value="{{ isset($search['email']) ? $search['email'] : '' }}">
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
                                        <th>اسم الفريق</th>
                                        <th>اسم المستخدم</th>
                                        <th>البريد الألكتروني</th>
                                        <th>الدور</th>
                                        <th>حالة فريق العمل</th>
                                        <th>تم الأنشاء في</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roleUsers as $key => $user)
                                        <tr>
                                            <td>{{ $key + $roleUsers->firstItem() }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ str_replace('_', ' ', ucfirst(optional($user->role)->name)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->status)
                                                    <div class="badge badge-success">نشط</div>
                                                @else
                                                    <div class="badge badge-danger">غير نشط</div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->created_at->format('m/d/Y h:i A') }}
                                            </td>
                                            <td>
                                                <button data-href="{{ route('admin.staff.update', $user->id) }}"
                                                    data-user="{{ $user }}"
                                                    class="btn btn-md btn-primary update"><i class="fa fa-pen"></i></button>

                                                <a href="{{ route('admin.login.staff', $user) }}" style="width: 35px; height: 35px" title="تسجيل الدخول"
                                                    class="btn btn-info btn-sm"><i class="fas fa-sign-in-alt"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="100%">لم يتم العثور علي اي فريق عمل</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Or Update Model -->
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
                                <label for="role">الدور
                                    <span class="text-danger">*</span> </label>
                                <select name="role" id="role" class="form-control">
                                    <option value="0" disabled selected>اختر دور فريق العمل</option>
                                    @forelse($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                        <option disabled>لم يتم العثور علي اي أدوار</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="name">الأسم بالكامل <span class="text-danger">*</span> </label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="email">البريد الألكتروني <span class="text-danger">*</span> </label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="username">اسم المستخدم <span class="text-danger">*</span> </label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="password">كلمة المرور <span class="text-danger">*</span> </label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="">حالة فريق العمل</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="status" value="1" class="selectgroup-input">
                                        <span class="selectgroup-button">نشط</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="status" value="0" class="selectgroup-input">
                                        <span class="selectgroup-button">غير نشط</span>
                                    </label>
                                </div>
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
    <!------------------>
@endsection

@push('script')
    <script>
        $(function() {
            'use strict'

            $('.add').on('click', function() {
                const modal = $('#modelId');
                modal.find('form')[0].reset();
                modal.find('.modal-title').text("إنشاء فريق عمل جديد")
                modal.find('form').attr('action', '{{ route('admin.staff.create') }}');
                modal.modal('show');
            })

            $('.update').on('click', function() {
                const modal = $('#modelId');
                const user = $(this).data('user');

                modal.find('.modal-title').text("تعديل فريق العمل" + ' ' + user.name);
                modal.find('form')[0].reset();
                modal.find('#role').val(user.role_id);
                modal.find('#name').val(user.name);
                modal.find('#email').val(user.email);
                modal.find('#username').val(user.username);
                modal.find('#password').val('');
                modal.find('input[name="status"][value="' + user.status + '"]').prop('checked', true);
                modal.find('form').attr('action', $(this).data('href'));
                modal.modal('show');
            });
        })
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