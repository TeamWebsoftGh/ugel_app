<form method="POST" action="{{ route('account.change-password') }}" class="needs-validation" novalidate>
    @csrf
    @method("PUT")
    <div class="form-group">
        <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
        <input id="current_password" type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}"
               name="current_password" value="{{ old('current_password') }}" required>
        @error('current_password')
        <div class="text-danger" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="new_password" class="form-label">{{ __('New Password') }}</label>
        <input id="new_password" type="password" minlength="6" data-minlength-error="Minimum of 6 characters allowed"
               class="form-control" name="new_password" required>
        @error('new_password')
        <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="new_password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
        <input id="new_password_confirmation" data-match="#new-password" data-match-error="Passwords must match" type="password" required class="form-control" name="new_password_confirmation">
        @error('new_password_confirmation')
        <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            {{ __('Change Password') }}
        </button>
    </div>
</form>
