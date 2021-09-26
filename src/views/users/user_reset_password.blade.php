@component('panel.layouts.component', ['title' => 'ویرایش رمز عبور'])

    @slot('style')
    @endslot

    @slot('subject')
        <h1>
            <i class="fa fa-user"></i> ویرایش رمز عبور
        </h1>

        <p>این بخش
            برای ویرایش رمز عبور
            است.</p>
    @endslot

    @slot('breadcrumb')
        <li class="breadcrumb-item">ویرایش رمز عبور</li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles-assignment.index') }}">همه کاربران</a>
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
                        @component('components.collapse-card', ['id' => 'user_reset_password', 'show' => 'show','title' => 'ویرایش رمز عبور'])
                            @slot('body')
                                <form method="POST"
                                      action="{{ route('user.reset_password', $user) }}" enctype="multipart/form-data" autocomplete="off">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="first_name">
                                                    <strong>نام</strong>
                                                </label>
                                                <input disabled class="form-control"
                                                       name="first_name" id="first_name" value="{{ $user->full_name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">
                                                    <strong>نام کاربری</strong>
                                                </label>
                                                <input disabled class="form-control" name="username" id="username"
                                                       style="direction: ltr" value="{{ $user->username }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">
                                                    <strong>ایمیل</strong>
                                                </label>
                                                <input disabled id="email" type="text" style="direction: ltr" class="form-control" name="email"
                                                       value="{{ $user->email }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">رمز عبور جدید</label>
                                                <div class="input-group show-password-holder">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text fa fa-eye toggle-password text-primary cursor-icon" id="password_icon">
                                                        </span>
                                                    </div>
                                                    <input class="form-control password-input @error('password') is-invalid @enderror" type="password" name="password" id="password" value="" aria-describedby="password_icon">
                                                </div>
                                                @error('password')
                                                    <span class="text-danger" role="alert">
                                                        <small class="font-weight-bold">
                                                            {{ $message }}
                                                        </small>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password_confirmation">تکرار رمز عبور</label>
                                                <div class="input-group show-password-holder">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text fa fa-eye toggle-password text-primary cursor-icon" id="password_confirmation_icon">
                                                        </span>
                                                    </div>
                                                    <input class="form-control password-input @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" id="password_confirmation" value="" aria-describedby="password_confirmation_icon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success" type="submit">ثبت</button>
                                        </div>
                                    </div>
                                </form>
                            @endslot
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>

    @endslot

    @slot('script')
        <script>
            $('.show-password-holder').find('.toggle-password').click(function() {
                // let parent = $('.show-password-holder');
                $(this).toggleClass("fa-eye fa-eye-slash");
                let parent = $(this).closest('.show-password-holder');
                let input = parent.find('.password-input')
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                } else {
                    input.attr('type', 'password');
                }
            });
        </script>
    @endslot

@endcomponent
