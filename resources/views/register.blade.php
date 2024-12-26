@extends('layouts.app')

@section('content')
    <div class="container ">
        <form>
            @csrf
            <div class="auth-block">
                <div class="authorize-block">
                    <div class="text-center">
                        <span class="fs-4">Register</span>
                    </div>

                    <div class="authorize-fields"></div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Register</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary">< Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="module">
        $(() => {
            auth.renderForm('register');

            $('form').on('submit', async e => auth.send('register', e));
        });
    </script>
@endsection

