<x-guest-layout>
    @section('content')
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('images/signupcover.jpeg'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-body">
                            <form method="POST" id="register" name="register" action="{{ route('register') }}">
                                @csrf
                                <div class="flex flex-col mb-3">
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" aria-label="Name" value="{{ old('username') }}">
                                    @error('username') <p class="help-block"> {{$message}} </p>@enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="{{ old('email') }}">
                                    @error('email') <p class="help-block"> {{$message}} </p>@enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password">
                                    @error('password') <p class="help-block"> {{$message}} </p>@enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Reenter Password" aria-label="password_confirmation">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                                </div>
                                <p class="text-sm mt-3 mb-0">Already have an account? <a href="{{route('login')}}" class="text-dark font-weight-bolder">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endsection

    @push('footer_scripts')
    <script type="text/javascript">
        $("#register").validate({
            rules: {
                username: {
                    required: true
                },
                email: {
                    required: true,
                    maxlength: 191,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 191
                },
                password_confirmation: {
                    required: true,
                    minlength: 5,
                    maxlength: 191
                },
            },
            messages: {
                username: {
                    required: "Please enter username.",
                },
                email: {
                    required: "Please enter email address.",
                    maxlength: "Please enter no more than 100 characters."
                },
                password: {
                    required: "Please enter password.",
                    minlength: "Please enter Minimum 5 characters.",
                    maxlength: "Please enter no more than 100 characters."
                },
                password_confirmation: {
                    required: "Please enter password.",
                    minlength: "Please enter Minimum 5 characters.",
                    maxlength: "Please enter no more than 100 characters."
                },
            },
            errorPlacement: function($error, $element) {
                $error.appendTo($element.closest("div"));
            },
            onfocusout: function(element) {
                this.element(element);
            },
            submitHandler: function(form) {
                form.submit();
            },
        });
    </script>
    @endpush
</x-guest-layout>