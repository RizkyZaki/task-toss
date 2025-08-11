<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.plugins.top')
</head>

<body>

    <body class="nk-body npc-crypto bg-white pg-auth">
        <!-- app body @s -->
        <div class="nk-app-root">
            <div class="nk-split nk-split-page nk-split-lg">
                <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                    <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                        <a href="#" class="toggle btn btn-white btn-icon btn-light" data-target="athPromo"><em
                                class="icon ni ni-info"></em></a>
                    </div>
                    <div class="nk-block nk-block-middle nk-auth-body">
                        <div class="brand-logo pb-5">

                        </div>
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title">Masuk</h5>
                            </div>
                        </div><!-- .nk-block-head -->
                        <form>

                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" id="email"
                                        placeholder="Masukkan Email...">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password">Kata Sandi</label>
                                    <a class="link link-primary link-sm" tabindex="-1" href="{{ url('forgot') }}">Lupa
                                        Kata Sandi?</a>
                                </div>
                                <div class="form-control-wrap">
                                    <a tabindex="-1" href="#"
                                        class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                    </a>
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        placeholder="Masukkan Kata Sandi...">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn login btn-lg btn-primary btn-block">
                                    <div class="spinner-border visually-hidden" role="status"></div>Masuk
                                </button>
                            </div>
                        </form><!-- form -->
                        <div class="text-center pt-3">
                            <span class="sub-text">Belum punya akun?
                                <a href="{{ route('register') }}">Daftar Sekarang</a>
                            </span>
                        </div>
                    </div><!-- .nk-block -->

                </div><!-- nk-split-content -->
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
            </div><!-- nk-split -->
        </div><!-- app body @e -->
        <!-- JavaScript -->

        <!-- select region modal -->

    </body>
    @include('admin.plugins.bottom')
    <script>
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
    </script>
    <script src="{{ asset('custom/login.js') }}"></script>

</html>
