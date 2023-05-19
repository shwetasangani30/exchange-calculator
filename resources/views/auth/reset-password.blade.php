<x-guest-layout>
    @section('content')
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Reset your password</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" name="reset-pass" id="reset-pass" action="{{ route('password.store') }}">
                                        @csrf
                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="{{ old('email', $request->email) }}">
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
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Submit</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('/images/logincover.jpeg');background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"The more risk you take, the more you might lose too"</h4>
                                <p class="text-white position-relative">Nobody ever made high returns in the stock market without taking risk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @endsection

    @push('footer_scripts')
    <script type="text/javascript">
        $("#reset-pass").validate({
            rules: {
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