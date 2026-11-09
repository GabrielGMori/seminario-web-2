<form method="POST" action="{{ route('lang.switch') }}">
    @csrf
    <label class="visually-hidden" for="language">@lang('navigation.language')</label>
    <select class="form-select form-select-sm" id="language" name="locale" onchange="this.form.submit()">
        <option value="pt_BR" @selected(App::getLocale() === 'pt_BR')>Português</option>
        <option value="en" @selected(App::getLocale() === 'en')>English</option>
        <option value="es" @selected(App::getLocale() === 'es')>Español</option>
    </select>
</form>
