@component('panel.layouts.component', ['title' => 'دسترسی‌ها'])

    @slot('style')
    @endslot

    @slot('subject')
        <h1><i class="fa fa-users"></i> دسترسی‌ها </h1>
        <p>لیست دسترسی‌های تعریف شده برای مدیریت سطوح دسترسی.</p>
    @endslot

    @slot('breadcrumb')
        <li class="breadcrumb-item">دسترسی‌ها</li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles-assignment.index') }}">سطوح دسترسی</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('panel') }}">
                <i class="fa fa-home fa-lg"></i>
            </a>
        </li>
    @endslot

    @slot('content')
        <div class="row">
            <div class="col-md-12">
                @component('components.accordion')
                    @slot('cards')
                        @component('components.collapse-card', ['id' => 'permission_index', 'show' => 'show', 'title' => 'لیست دسترسی‌ها'])
                            @slot('body')
                                @component('components.collapse-search', ['show' => $show_filter])
                                    @slot('form')
                                        <form class="clearfix">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">نام</label>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                               value="{{ request('name') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="display_name">برچسب</label>
                                                        <input type="text" class="form-control" id="display_name" name="display_name"
                                                               value="{{ request('display_name') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary float-left">جستجو</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endslot
                                @endcomponent

                                <div class="mt-4">
                                    <a href={{ route('permissions.create') }} type="button" class="btn btn-primary"><i class="fa fa-plus"></i> ایجاد دسترسی</a>
                                </div>

                                @component('components.table')
                                    @slot('thead')
                                        <tr>
                                            <th>شناسه</th>
                                            <th>نام</th>
                                            <th>برچسب</th>
                                            <th>توضیحات</th>
                                            <th>فعالیت</th>
                                        </tr>
                                    @endslot
                                    @slot('tbody')
                                        @forelse ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    {{$permission->id}}
                                                </td>
                                                <td>
                                                    {{$permission->name}}
                                                </td>
                                                <td>
                                                    {{$permission->display_name}}
                                                </td>
                                                <td>
                                                    {{$permission->description}}
                                                </td>
                                                <td class="d-flex">
                                                    <a href="{{route('permissions.edit', $permission->id)}}"
                                                       class="btn btn-sm btn-primary mr-2">ویرایش</a>
                                                    <a  href="#"
                                                        class="btn btn-sm btn-danger destroy_ajax" data-id="{{$permission->id}}">
                                                        حذف
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">موردی برای نمایش وجود ندارد.</td>
                                            </tr>
                                        @endforelse
                                    @endslot
                                @endcomponent
                                {{--Paginate section--}}
                                {{ $permissions->withQueryString()->links() }}
                            @endslot
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>


    @endslot

    @slot('script')
        <script>
            $(".destroy_ajax").on('click', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'آیا برای حذف اطمینان دارید؟',
                    icon: 'warning',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn btn-danger mx-2',
                        cancelButton: 'btn btn-light mx-2'
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'حذف',
                    cancelButtonText: 'لغو',
                    showClass: {
                        popup: 'animated fadeInDown'
                    },
                    hideClass: {
                        popup: 'animated fadeOutUp'
                    }
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "delete",
                                url: baseUrl + '/panel/permissions/' + id,
                                dataType: 'json',
                                success: function (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        text: 'عملیات حذف با موفقیت انجام شد.',
                                        confirmButtonText:'تایید',
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                        },
                                        buttonsStyling: false,
                                        showClass: {
                                            popup: 'animated fadeInDown'
                                        },
                                        hideClass: {
                                            popup: 'animated fadeOutUp'
                                        }
                                    })
                                        .then((response) => {
                                            location.reload();
                                        });
                                }
                            });
                        }
                    });
            });
        </script>
    @endslot

@endcomponent
