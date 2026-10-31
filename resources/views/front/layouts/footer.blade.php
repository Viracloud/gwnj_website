<footer class="site">
    <div class="shell">
        <div class="cols">
            <div class="about">
                <img src="{{ asset('uploads/'.$global_setting->logo_2) }}" alt="{{ config('app.name') }}">
                <p>{{ __('An international network campaigning for justice, equality and freedom for women.') }}</p>
                <div class="social">
                    @if($global_setting->footer_facebook != '')<a href="{{ $global_setting->footer_facebook }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>@endif
                    @if($global_setting->footer_twitter != '')<a href="{{ $global_setting->footer_twitter }}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>@endif
                    @if($global_setting->footer_linkedin != '')<a href="{{ $global_setting->footer_linkedin }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>@endif
                    @if($global_setting->footer_instagram != '')<a href="{{ $global_setting->footer_instagram }}" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>@endif
                </div>
            </div>

            @php($donate_item = $global_nav->firstWhere('route_name', 'donate'))
            <div>
                <h4>{{ __('About') }}</h4>
                <ul>
                    @foreach($global_footer_items->where('footer_column', 'about') as $item)
                    <li><a href="{{ $item->resolvedUrl() }}" @if($item->open_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label() }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4>{{ __('Our work') }}</h4>
                <ul>
                    @foreach($global_footer_items->where('footer_column', 'work') as $item)
                    <li><a href="{{ $item->resolvedUrl() }}" @if($item->open_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label() }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4>{{ __('Get involved') }}</h4>
                <ul>
                    @if($global_menu->menu_login_status == 'Show')<li><a href="{{ route('register') }}">{{ __('Become a member') }}</a></li>@endif
                    @if($donate_item)<li><a href="{{ $donate_item->resolvedUrl() }}">{{ $donate_item->label() }}</a></li>@endif
                    <li><a href="{{ route('contact') }}">{{ __('Volunteer or coordinate') }}</a></li>
                </ul>
            </div>

            @php($transparency_item = \App\Models\MenuItem::where('label', 'Transparency & Accountability')->first())
            <div>
                <h4>{{ __('Accountability') }}</h4>
                <ul>
                    @if($transparency_item)<li><a href="{{ $transparency_item->resolvedUrl() }}">{{ $transparency_item->label() }}</a></li>@endif
                    <li><a href="{{ route('terms') }}">{{ __('Terms') }}</a></li>
                    <li><a href="{{ route('privacy') }}">{{ __('Privacy policy') }}</a></li>
                    @if($global_setting->footer_phone != '')<li><a href="tel:{{ $global_setting->footer_phone }}" dir="ltr" style="unicode-bidi:isolate">{{ $global_setting->footer_phone }}</a></li>@endif
                    @if($global_setting->footer_email != '')<li><a href="mailto:{{ $global_setting->footer_email }}" dir="ltr" style="unicode-bidi:isolate">{{ $global_setting->footer_email }}</a></li>@endif
                </ul>
            </div>
        </div>

        <div style="max-width:420px;margin-top:var(--s7)">
            <h4>{{ __('Subscribe') }}</h4>
            <form action="{{ route('subscriber_send_email') }}" class="footer-subscribe form_subscribe_ajax" method="post">
                @csrf
                <input name="email" type="email" required placeholder="{{ __('Your email address') }}">
                <button type="submit" aria-label="Subscribe"><i class="fa-regular fa-envelope"></i></button>
            </form>
            <div id="loader"></div>
        </div>

        <div class="legal">
            <span>&copy; {{ date('Y') }} GWJN. {{ tf($global_setting,'footer_copyright') }}</span>
            <a href="{{ route('privacy') }}">{{ __('Privacy policy') }}</a>
            <a href="{{ route('terms') }}">{{ __('Terms') }}</a>
        </div>
    </div>
</footer>

@push('scripts')
<script>
$(".form_subscribe_ajax").on('submit', function(e){
    e.preventDefault();
    $('#loader').show();
    var form = this;
    $.ajax({
        url:$(form).attr('action'),
        method:$(form).attr('method'),
        data:new FormData(form),
        processData:false,
        dataType:'json',
        contentType:false,
        success:function(data)
        {
            $('#loader').hide();
            if(data.code == 0)
            {
                if (typeof data.error_message === 'string') {
                    iziToast.error({ title: 'Error', message: data.error_message, position: 'topRight' });
                } else {
                    $.each(data.error_message, function(prefix, val) {
                        iziToast.error({ title: 'Error', message: val[0], position: 'topRight' });
                    });
                }
            }
            else if(data.code == 1)
            {
                $(form)[0].reset();
                iziToast.success({ title: 'Success', message: data.success_message, position: 'topRight' });
            }
        },
        error:function()
        {
            $('#loader').hide();
            iziToast.error({ title: 'Error', message: 'Request failed. Please try again.', position: 'topRight' });
        }
    });
});
</script>
@endpush
