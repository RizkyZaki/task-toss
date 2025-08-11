<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.plugins.top')
</head>

<body class="nk-body npc-crypto bg-white pg-auth">
    <div class="nk-app-root">
        <div class="nk-split nk-split-page nk-split-lg">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                    <a href="#" class="toggle btn btn-white btn-icon btn-light" data-target="athPromo">
                        <em class="icon ni ni-info"></em>
                    </a>
                </div>

                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="{{ url('/') }}" class="logo-link">
                            <img class="logo-img logo-img-lg" src="{{ asset('client/assets/img/logo_login.png') }}"
                                srcset="{{ asset('client/assets/img/logo_login.png') }}" alt="logo">
                        </a>
                    </div>

                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Daftar Akun</h5>
                            <div class="nk-block-des">
                                <p>Buat akun untuk mulai mengakses kelas.</p>
                            </div>
                        </div>
                    </div>

                    {{-- alert server-side (opsional) --}}
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" id="register-form">
                        @csrf
                        {{-- role default: student --}}
                        <input type="hidden" name="role" value="student">

                        <div class="form-group">
                            <label class="form-label" for="name">Nama Lengkap</label>
                            <div class="form-control-wrap">
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="Masukkan Nama..." value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">No Handphone</label>
                            <div class="form-control-wrap">
                                <input type="text" name="phone" id="phone"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    placeholder="Masukkan No Handphone..." value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <div class="form-control-wrap">
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email..." value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">Kata Sandi</label>
                            </div>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="Masukkan Kata Sandi..." required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                            </div>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password_confirmation">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control form-control-lg" placeholder="Ulangi Kata Sandi..." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn register btn-lg btn-primary btn-block">
                                <div class="spinner-border visually-hidden" role="status"></div>Daftar
                            </button>
                        </div>
                    </form>

                    <div class="text-center pt-3">
                        <span class="sub-text">Sudah punya akun?
                            <a href="{{ route('login') }}">Masuk</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="nk-split-content nk-split-stretch"
                style="
                    background-image: linear-gradient(
                        to right,
                        #007d6c 0%,
                        #00a78b 25%,
                        #00CEA9 50%,
                        #4edfc2 75%,
                        #a1f4e1 100%
                    ) !important;
                ">
            </div>
        </div>
    </div>

    @include('admin.plugins.bottom')
</body>

</html>
