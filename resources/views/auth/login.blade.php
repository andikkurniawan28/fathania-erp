@extends('template.sneat.auth')

@section('content')
    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">{{ ucwords(str_replace('_', ' ', 'username')) }}</label>
            <input type="text" class="form-control" id="username" name="username"
                placeholder="Enter your username" autofocus required/>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">{{ ucwords(str_replace('_', ' ', 'password')) }}</label>
                {{-- <a href="auth-forgot-password-basic.html">
                    <small>Forgot Password?</small>
                </a> --}}
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                <span class="input-group-text cursor-pointer" id="showPassword"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
        </div>
    </form>

    <script>
        const showPassword = document.getElementById('showPassword');
        const passwordInput = document.getElementById('password');

        showPassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bx-hide');
            this.querySelector('i').classList.toggle('bx-show');
        });
    </script>
@endsection
