@component('panel.layouts.component', ['title' => 'کاربران'])

    @slot('style')
    @endslot

    @slot('subject')
        <h1><i class="fa fa-users"></i> کاربران </h1>
        <p>مدیریت سطوح دسترسی کاربران، اعطای دسترسی و نقش به کاربران.</p>
    @endslot

    @slot('breadcrumb')
        <li class="breadcrumb-item">لیست کاربران</li>
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
                        @component('components.collapse-card', ['id' => 'role_assignment_index', 'show' => 'show', 'title' => 'لیست کاربران'])
                            @slot('body')
                                @component('components.collapse-search', ['show' => $show_filter])
                                    @slot('form')
                                        <form class="clearfix" method="get">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="full_name">نام ونام خانوادگی</label>
                                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ request('full_name') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mobile">موبایل</label>
                                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{ request('mobile') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">ایمیل</label>
                                                        <input type="text" class="form-control" id="email" name="email" value="{{ request('email') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="role">نقش</label>
                                                        <select name="role" id="role"
                                                                class="form-control select2">
                                                            <option value="">
                                                                انتخاب کنید...
                                                            </option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : ''}}>
                                                                    {{ $role->display_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary float-left">جستجو</button>
                                        </form>
                                    @endslot
                                @endcomponent

                                <div class="mt-4">
                                    <a href={{ route('users.create') }} type="button" class="btn btn-primary"><i class="fa fa-plus"></i> ایجاد کاربر</a>
                                </div>

                                @component('components.table')
                                    @slot('thead')
                                        <tr>
                                            <th>شناسه</th>
                                            <th>نام</th>
                                            <th>نام خانوادگی</th>
                                            <th>موبایل</th>
                                            <th>ایمیل</th>
                                            <th># نقش‌ها</th>
                                            <th>فعالیت</th>
                                        </tr>
                                    @endslot
                                    @slot('tbody')
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>
                                                    {{$user->getKey()}}
                                                </td>
                                                <td>
                                                    {{$user->first_name ?? '-'}}
                                                </td>
                                                <td>
                                                    {{$user->last_name ?? '-'}}
                                                </td>
                                                <td>
                                                    {{digitsToEastern($user->mobile) ?? '-'}}
                                                </td>
                                                <td>
                                                    {{$user->email ?? '-'}}
                                                </td>
                                                <td>
                                                    {{ count($user->roles) > 0 ? implode(',',$user->roles->pluck('display_name')->toArray()) : '-' }}
                                                </td>
                                                <td>
                                                    <div class="dropdown show">
                                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            فعالیت
                                                        </a>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <a class="dropdown-item" href="{{route('roles-assignment.edit', ['roles_assignment' => $user->id, 'model' => $modelKey])}}">ویرایش دسترسی</a>
                                                            <a class="dropdown-item" href="{{route('users.edit', $user->id)}}">ویرایش اطلاعات</a>
                                                            <a class="dropdown-item" href="{{route('user.reset_password', ['user' => $user->id])}}">ویرایش رمز عبور</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">موردی برای نمایش وجود ندارد.</td>
                                            </tr>
                                        @endforelse
                                    @endslot
                                @endcomponent

                                {{--Paginate section--}}
                                @if ($modelKey)
                                    {{ $users->withQueryString()->links() }}
                                @endif
                            @endslot
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>
    @endslot

    @slot('script')
        <script>
            $('.select2').select2({
                "theme": "bootstrap"
            });
        </script>
    @endslot

@endcomponent
