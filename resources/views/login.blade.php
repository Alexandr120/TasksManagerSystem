@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="">
            @csrf
            <div class="auth-block">
                <div class="authorize-block">
                    <div class="text-center">
                        <span class="fs-4">{{ __('Sign In') }}</span>
                    </div>

                    <div class="authorize-fields"></div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">{{ __('Login') }}</button>
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Register') }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="module">
        $(() => {
            auth.renderForm('login');

            if (sessionStorage.getItem('register')) {
                main.showAlert('success', '{{ __('You have successfully registered! Login with your credentials.') }}')
                sessionStorage.removeItem('register');
            }

            $('form').on('submit',  e => auth.send('login', e));
        });
    </script>
@endsection
