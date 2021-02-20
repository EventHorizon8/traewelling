<div class="card mt-3">
    <div class="card-header">{{ __('settings.title-password') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('password.change') }}">
            @csrf
            <input type="hidden" name="username" autocomplete="username">
            <div class="form-group row">
                <label for="name"
                       class="col-md-4 col-form-label text-md-right">{{ __('settings.current-password') }}</label>

                <div class="col-md-6">
                    <input id="currentpassword" type="password"
                           class="form-control @error('currentpassword') is-invalid @enderror"
                           name="currentpassword" autocomplete="current-password">

                    @error('currentpassword')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="email"
                       class="col-md-4 col-form-label text-md-right">{{ __('settings.new-password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror" name="password"
                           autocomplete="new-password" required>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="email"
                       class="col-md-4 col-form-label text-md-right">{{ __('settings.confirm-password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password_confirmation" autocomplete="new-password" required>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('settings.btn-update') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>