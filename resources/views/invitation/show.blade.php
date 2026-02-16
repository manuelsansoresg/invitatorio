<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invitation->category->name }} - Invitación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&family=Montserrat:wght@300;400;700&family=Great+Vibes&family=Dancing+Script:wght@400;600&family=Allura&family=Alex+Brush&family=Pacifico&family=Satisfy&family=Parisienne&family=Yellowtail&family=Italianno&family=Tangerine:wght@400;700&family=Courgette&family=Marck+Script&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        /* Dynamic Styles from Database */
        .anim {
            opacity: 0;
            transform: translateY(14px);
            transition-property: opacity, transform;
        }
        .anim.is-in {
            opacity: 1;
            transform: none;
        }
        .anim[data-anim="fade"] { transform: translateY(0); }
        .anim[data-anim="fade-down"] { transform: translateY(-14px); }
        .anim[data-anim="slide-left"] { transform: translateX(-18px); }
        .anim[data-anim="slide-right"] { transform: translateX(18px); }
        .anim[data-anim="zoom-in"] { transform: scale(0.96); }
        .hero-title {
            font-family: '{{ $invitation->design_settings['hero']['font_family'] ?? 'Playfair Display' }}', serif;
            color: {{ $invitation->design_settings['hero']['text_color'] ?? '#ffffff' }};
        }
        .dedication-section {
            background-color: {{ $invitation->design_settings['dedication']['background_color'] ?? '#f9fafb' }};
            color: {{ $invitation->design_settings['dedication']['text_color'] ?? '#1f2937' }};
            @if(isset($invitation->design_settings['dedication']['background_image']) && $invitation->design_settings['dedication']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['dedication']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .dedication-section h2 {
            color: {{ $invitation->design_settings['dedication']['text_color'] ?? '#111827' }};
        }
        .details-section {
            background-color: {{ $invitation->design_settings['details']['background_color'] ?? '#ffffff' }};
            color: {{ $invitation->design_settings['details']['text_color'] ?? '#1f2937' }};
            @if(isset($invitation->design_settings['details']['background_image']) && $invitation->design_settings['details']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['details']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .details-section h2, .details-section h3, .details-section h4 {
            color: {{ $invitation->design_settings['details']['text_color'] ?? '#111827' }};
        }
        .ceremony-card {
            background-color: {{ $invitation->design_settings['ceremony']['background_color'] ?? '#ffffff' }};
            color: {{ $invitation->design_settings['ceremony']['text_color'] ?? '#1f2937' }};
            border-color: {{ $invitation->design_settings['ceremony']['border_color'] ?? '#e5e7eb' }};
            @if(isset($invitation->design_settings['ceremony']['background_image']) && $invitation->design_settings['ceremony']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['ceremony']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .ceremony-card h3, .ceremony-card p {
            color: {{ $invitation->design_settings['ceremony']['text_color'] ?? '#1f2937' }};
        }
        .reception-card {
            background-color: {{ $invitation->design_settings['reception']['background_color'] ?? '#ffffff' }};
            color: {{ $invitation->design_settings['reception']['text_color'] ?? '#1f2937' }};
            border-color: {{ $invitation->design_settings['reception']['border_color'] ?? '#e5e7eb' }};
            @if(isset($invitation->design_settings['reception']['background_image']) && $invitation->design_settings['reception']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['reception']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .reception-card h3, .reception-card p {
            color: {{ $invitation->design_settings['reception']['text_color'] ?? '#1f2937' }};
        }
        .extra-info-section {
            background-color: {{ $invitation->design_settings['extra_info']['background_color'] ?? '#f9fafb' }};
            color: {{ $invitation->design_settings['extra_info']['text_color'] ?? '#1f2937' }};
            @if(isset($invitation->design_settings['extra_info']['background_image']) && $invitation->design_settings['extra_info']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['extra_info']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .extra-info-section h4 {
            color: {{ $invitation->design_settings['extra_info']['text_color'] ?? '#111827' }};
        }
        .rsvp-section {
            background-color: {{ $invitation->design_settings['rsvp']['background_color'] ?? '#111827' }};
            color: {{ $invitation->design_settings['rsvp']['text_color'] ?? '#ffffff' }};
            @if(isset($invitation->design_settings['rsvp']['background_image']) && $invitation->design_settings['rsvp']['background_image'])
                background-image: url('{{ url('uploads/' . $invitation->design_settings['rsvp']['background_image']) }}');
                background-size: cover;
                background-position: center;
            @endif
        }
        .rsvp-section h2, .rsvp-section p {
            color: {{ $invitation->design_settings['rsvp']['text_color'] ?? '#ffffff' }};
        }

        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }
        .countdown-number {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .countdown-label {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
        }
        .prose img {
            max-width: 320px;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .prose figure {
            margin: 0 auto 1rem auto;
        }
        .prose figure > figcaption {
            display: none;
        }
    </style>
    <!-- Import Google Fonts dynamically if needed -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
    
    @if(session('success'))
        <div class="fixed top-0 left-0 w-full bg-green-500 text-white text-center py-4 z-50 shadow-lg" 
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
    @endif

    @if($invitation->background_music_path)
        @php
            $musicAutoplay = $invitation->background_music_autoplay ?? true;
        @endphp
        <audio @if($musicAutoplay) autoplay @endif loop controls class="fixed bottom-4 right-4 z-50">
            <source src="{{ asset('uploads/' . $invitation->background_music_path) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif

    <!-- Hero Section -->
    @php
        $imagePath = 'uploads/' . $invitation->cover_photo_path;
    @endphp
    
    <div class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image with Parallax -->
        <div class="absolute inset-0 z-0">
            <img src="/{{ $imagePath }}" 
                 class="w-full h-full object-cover object-top fixed top-0 left-0 -z-10" 
                 alt="Fondo de la invitación">
        </div>
        
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        
        <!-- Content -->
        <div class="relative z-20 text-center text-white px-4">
            <h1 class="text-5xl md:text-7xl font-bold mb-4 italic drop-shadow-lg hero-title">{{ $invitation->category->name }}</h1>
            <p class="text-xl md:text-2xl mb-8 drop-shadow-md">Estás invitado a celebrar con nosotros</p>
            
            @if($invitation->event_date)
                <div id="countdown" class="flex justify-center flex-wrap mt-8 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
                    <!-- Javascript will populate this -->
                </div>
                <p class="mt-8 text-lg drop-shadow-md">{{ $invitation->event_date->format('d F Y') }}</p>
            @endif
        </div>
    </div>

    <!-- Dynamic Content Blocks -->
    @if($invitation->content_blocks)
        @foreach($invitation->content_blocks as $block)
            @php
                $data = $block['data'];
                $type = $block['type'];
                
                $bgColor = $data['background_color'] ?? '#ffffff';
                $bgImage = isset($data['background_image']) ? url('uploads/' . $data['background_image']) : null;
                $textColor = $data['text_color'] ?? '#1f2937';
                $paddingY = $data['padding_y'] ?? 'py-16';
                $fontFamily = $data['font_family'] ?? ($invitation->design_settings['hero']['font_family'] ?? 'Playfair Display');
                $textSize = $data['text_size'] ?? 'text-lg';
                $sizeScale = [
                    'text-base' => '1.25rem',
                    'text-lg' => '1.5rem',
                    'text-xl' => '2.4rem',
                    'text-2xl' => '3.6rem',
                ];
                
                $style = "background-color: {$bgColor}; color: {$textColor}; font-family: {$fontFamily}, serif;";
                if ($bgImage) {
                    $style .= "background-image: url('{$bgImage}'); background-size: cover; background-position: center;";
                }
                $marginTopKey = $data['margin_top'] ?? 'md';
                $marginBottomKey = $data['margin_bottom'] ?? 'md';
                $marginScale = [
                    'none' => '0',
                    'xs' => '8px',
                    'sm' => '16px',
                    'md' => '24px',
                    'lg' => '32px',
                    'xl' => '48px',
                ];
                $sectionMarginTop = $marginScale[$marginTopKey] ?? $marginScale['md'];
                $sectionMarginBottom = $marginScale[$marginBottomKey] ?? $marginScale['md'];
            @endphp

            @php
                $enableAnim = (bool) ($data['enable_animation'] ?? false);
                $animPreset = $data['animation_preset'] ?? 'fade-up';
                $animDuration = (int) ($data['animation_duration'] ?? 700);
                $animDelay = (int) ($data['animation_delay'] ?? 0);
                $animClass = $enableAnim ? 'anim' : '';
                $animData = $enableAnim ? "data-anim=\"{$animPreset}\" data-duration=\"{$animDuration}\" data-delay=\"{$animDelay}\"" : '';
                $animStyle = $enableAnim ? "transition-duration: {$animDuration}ms; transition-delay: {$animDelay}ms;" : '';
            @endphp

            <section class="{{ $paddingY }} w-full relative {{ $animClass }}" {!! $animData ? $animData : '' !!} style="{{ $style }} margin-top: {{ $sectionMarginTop }}; margin-bottom: {{ $sectionMarginBottom }}; {{ $animStyle }}">
                <!-- Overlay if background image exists to improve text readability -->
                @if($bgImage)
                    <div class="absolute inset-0 bg-black bg-opacity-30 z-0"></div>
                @endif

                <div class="container mx-auto px-4 relative z-10">
                    
                    {{-- BLOCK: DEDICATION --}}
                    @if($type === 'dedication')
                        @php
                            $titleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $titleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $bodyFontFamily = $data['body_font_family'] ?? $fontFamily;
                            $bodyTextSize = $data['body_text_size'] ?? $textSize;
                            $titleColor = $data['title_color'] ?? $textColor;
                            $bodyColor = $data['body_color'] ?? $textColor;
                            $titleFontSize = $sizeScale[$titleTextSize] ?? $sizeScale['text-xl'];
                            $bodyFontSize = $sizeScale[$bodyTextSize] ?? $sizeScale['text-lg'];
                        @endphp
                        <div class="text-center max-w-4xl mx-auto">
                            <h2 class="font-bold mb-8" style="font-family: '{{ $titleFontFamily }}', serif; font-size: {{ $titleFontSize }}; color: {{ $titleColor }};">
                                {{ $data['title'] }}
                            </h2>
                            <p class="leading-relaxed whitespace-pre-line" style="font-family: '{{ $bodyFontFamily }}', serif; font-size: {{ $bodyFontSize }}; color: {{ $bodyColor }};">
                                {{ $data['content'] }}
                            </p>
                        </div>
                    @endif

                    {{-- BLOCK: CEREMONY --}}
                    @if($type === 'ceremony')
                        @php
                            $cardBgColor = $data['card_background_color'] ?? '#ffffff';
                            $cardBgImage = isset($data['card_background_image']) ? url('uploads/' . $data['card_background_image']) : null;
                            $cardTextColor = $data['card_text_color'] ?? $textColor;
                            $cardBorderColor = $data['card_border_color'] ?? '#e5e7eb';
                            $cardShowBorder = $data['card_show_border'] ?? true;
                            $cardShowShadow = $data['card_show_shadow'] ?? false;
                            $borderClass = $cardShowBorder ? 'border' : 'border-0';
                            $shadowClass = $cardShowShadow ? 'shadow-sm' : 'shadow-none';
                            $cardStyle = "background-color: {$cardBgColor}; color: {$cardTextColor};";
                            if ($cardShowBorder) {
                                $cardStyle .= " border-color: {$cardBorderColor};";
                            }
                            if ($cardBgImage) {
                                $cardStyle .= "background-image: url('{$cardBgImage}'); background-size: cover; background-position: center;";
                            }
                            $cardWidthKey = $data['card_max_width'] ?? 'max-w-lg';
                            $cardWidthMap = [
                                'max-w-sm' => '280px',
                                'max-w-md' => '380px',
                                'max-w-lg' => '520px',
                                'max-w-xl' => '960px',
                            ];
                            $cardMaxWidth = $cardWidthMap[$cardWidthKey] ?? $cardWidthMap['max-w-lg'];
                            $iconSize = $data['icon_size'] ?? 'h-16 w-16';
                            $iconSizePxMap = [
                                'h-12 w-12' => '64px',
                                'h-16 w-16' => '96px',
                                'h-20 w-20' => '128px',
                                'h-24 w-24' => '160px',
                            ];
                            $iconSizePx = $iconSizePxMap[$iconSize] ?? '96px';
                            $ceremonyTitleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $ceremonyTitleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $ceremonyTitleColor = $data['title_color'] ?? $cardTextColor;
                            $ceremonyContentFontFamily = $data['content_font_family'] ?? $fontFamily;
                            $ceremonyContentTextSize = $data['content_text_size'] ?? $textSize;
                            $ceremonyContentColor = $data['content_color'] ?? $cardTextColor;
                            $ceremonyTimeFontFamily = $data['time_font_family'] ?? $ceremonyContentFontFamily;
                            $ceremonyTimeTextSize = $data['time_text_size'] ?? $ceremonyContentTextSize;
                            $ceremonyTimeColor = $data['time_color'] ?? $ceremonyContentColor;
                            $ceremonyTitleFontSize = $sizeScale[$ceremonyTitleTextSize] ?? $sizeScale['text-xl'];
                            $ceremonyContentFontSize = $sizeScale[$ceremonyContentTextSize] ?? $sizeScale['text-lg'];
                            $ceremonyTimeFontSize = $sizeScale[$ceremonyTimeTextSize] ?? $ceremonyContentFontSize;
                            $ceremonyTimeValue = $data['time'] ?? null;
                            if (empty($ceremonyTimeValue) && $invitation->ceremony_time) {
                                $ceremonyTimeValue = $invitation->ceremony_time->format('h:i a');
                            }
                            $buttonFontFamily = $data['button_font_family'] ?? $fontFamily;
                            $buttonTextSize = $data['button_text_size'] ?? 'text-base';
                            $buttonTextColor = $data['button_text_color'] ?? '#ffffff';
                            $buttonBackgroundColor = $data['button_background_color'] ?? '#111827';
                            $buttonFontSize = $sizeScale[$buttonTextSize] ?? $sizeScale['text-base'];
                        @endphp
                        <div class="text-center mx-auto rounded-lg p-8 {{ $shadowClass }} {{ $borderClass }} {{ $textSize }}" style="{{ $cardStyle }} max-width: {{ $cardMaxWidth }};">
                            @if(isset($data['icon_image']) && $data['icon_image'])
                                <img src="{{ url('uploads/' . $data['icon_image']) }}" class="mx-auto mb-4 object-contain" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};">
                            @else
                                <x-icon name="{{ $data['icon'] ?? 'heroicon-o-home' }}" class="mx-auto mb-4 opacity-80" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};" />
                            @endif
                            
                            <h3 class="font-bold mb-4" style="font-family: '{{ $ceremonyTitleFontFamily }}', serif; font-size: {{ $ceremonyTitleFontSize }}; color: {{ $ceremonyTitleColor }};">
                                {{ $data['title'] ?? 'Ceremonia' }}
                            </h3>
                            @if(!empty($ceremonyTimeValue))
                                <p class="font-bold mb-4" style="font-family: '{{ $ceremonyTimeFontFamily }}', serif; font-size: {{ $ceremonyTimeFontSize }}; color: {{ $ceremonyTimeColor }};">
                                    {{ $ceremonyTimeValue }}
                                </p>
                            @endif
                            @if($data['image'])
                                <img src="{{ url('uploads/' . $data['image']) }}" class="mx-auto mb-4 max-w-xs rounded-lg shadow-md object-contain">
                            @endif
                            @if($data['location_name'])
                                <p class="font-semibold mb-2" style="font-family: '{{ $ceremonyContentFontFamily }}', serif; font-size: {{ $ceremonyContentFontSize }}; color: {{ $ceremonyContentColor }};">
                                    {{ $data['location_name'] }}
                                </p>
                            @endif
                            @if($data['address'])
                                <p class="mb-4" style="font-family: '{{ $ceremonyContentFontFamily }}', serif; font-size: {{ $ceremonyContentFontSize }}; color: {{ $ceremonyContentColor }};">
                                    {{ $data['address'] }}
                                </p>
                            @endif
                            @if($data['map_link'])
                                <a
                                    href="{{ $data['map_link'] }}"
                                    target="_blank"
                                    class="inline-block px-6 py-2 rounded-full hover:opacity-90 transition"
                                    style="font-family: '{{ $buttonFontFamily }}', serif; font-size: {{ $buttonFontSize }}; color: {{ $buttonTextColor }}; background-color: {{ $buttonBackgroundColor }};"
                                >
                                    {{ $data['button_text'] ?? 'Ver mapa' }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- BLOCK: RECEPTION --}}
                    @if($type === 'reception')
                        @php
                            $cardBgColor = $data['card_background_color'] ?? '#ffffff';
                            $cardBgImage = isset($data['card_background_image']) ? url('uploads/' . $data['card_background_image']) : null;
                            $cardTextColor = $data['card_text_color'] ?? $textColor;
                            $cardBorderColor = $data['card_border_color'] ?? '#e5e7eb';
                            $cardShowBorder = $data['card_show_border'] ?? true;
                            $cardShowShadow = $data['card_show_shadow'] ?? false;
                            $borderClass = $cardShowBorder ? 'border' : 'border-0';
                            $shadowClass = $cardShowShadow ? 'shadow-sm' : 'shadow-none';
                            $cardStyle = "background-color: {$cardBgColor}; color: {$cardTextColor};";
                            if ($cardShowBorder) {
                                $cardStyle .= " border-color: {$cardBorderColor};";
                            }
                            if ($cardBgImage) {
                                $cardStyle .= "background-image: url('{$cardBgImage}'); background-size: cover; background-position: center;";
                            }
                            $cardWidthKey = $data['card_max_width'] ?? 'max-w-lg';
                            $cardWidthMap = [
                                'max-w-sm' => '280px',
                                'max-w-md' => '380px',
                                'max-w-lg' => '520px',
                                'max-w-xl' => '960px',
                            ];
                            $cardMaxWidth = $cardWidthMap[$cardWidthKey] ?? $cardWidthMap['max-w-lg'];
                            $iconSize = $data['icon_size'] ?? 'h-16 w-16';
                            $iconSizePxMap = [
                                'h-12 w-12' => '64px',
                                'h-16 w-16' => '96px',
                                'h-20 w-20' => '128px',
                                'h-24 w-24' => '160px',
                            ];
                            $iconSizePx = $iconSizePxMap[$iconSize] ?? '96px';
                            $receptionTitleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $receptionTitleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $receptionTitleColor = $data['title_color'] ?? $cardTextColor;
                            $receptionContentFontFamily = $data['content_font_family'] ?? $fontFamily;
                            $receptionContentTextSize = $data['content_text_size'] ?? $textSize;
                            $receptionContentColor = $data['content_color'] ?? $cardTextColor;
                            $receptionTimeFontFamily = $data['time_font_family'] ?? $receptionContentFontFamily;
                            $receptionTimeTextSize = $data['time_text_size'] ?? $receptionContentTextSize;
                            $receptionTimeColor = $data['time_color'] ?? $receptionContentColor;
                            $receptionTitleFontSize = $sizeScale[$receptionTitleTextSize] ?? $sizeScale['text-xl'];
                            $receptionContentFontSize = $sizeScale[$receptionContentTextSize] ?? $sizeScale['text-lg'];
                            $receptionTimeFontSize = $sizeScale[$receptionTimeTextSize] ?? $receptionContentFontSize;
                            $receptionTimeValue = $data['time'] ?? null;
                            if (empty($receptionTimeValue) && $invitation->reception_time) {
                                $receptionTimeValue = $invitation->reception_time->format('h:i a');
                            }
                            $receptionButtonFontFamily = $data['button_font_family'] ?? $fontFamily;
                            $receptionButtonTextSize = $data['button_text_size'] ?? 'text-base';
                            $receptionButtonTextColor = $data['button_text_color'] ?? '#ffffff';
                            $receptionButtonBackgroundColor = $data['button_background_color'] ?? '#111827';
                            $receptionButtonFontSize = $sizeScale[$receptionButtonTextSize] ?? $sizeScale['text-base'];
                        @endphp
                        <div class="text-center mx-auto rounded-lg p-8 {{ $shadowClass }} {{ $borderClass }} {{ $textSize }}" style="{{ $cardStyle }} max-width: {{ $cardMaxWidth }};">
                            @if(isset($data['icon_image']) && $data['icon_image'])
                                <img src="{{ url('uploads/' . $data['icon_image']) }}" class="mx-auto mb-4 object-contain" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};">
                            @else
                                <x-icon name="{{ $data['icon'] ?? 'heroicon-o-musical-note' }}" class="mx-auto mb-4 opacity-80" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};" />
                            @endif
                            
                            <h3 class="font-bold mb-4" style="font-family: '{{ $receptionTitleFontFamily }}', serif; font-size: {{ $receptionTitleFontSize }}; color: {{ $receptionTitleColor }};">
                                {{ $data['title'] ?? 'Recepción' }}
                            </h3>
                            @if(!empty($receptionTimeValue))
                                <p class="font-bold mb-4" style="font-family: '{{ $receptionTimeFontFamily }}', serif; font-size: {{ $receptionTimeFontSize }}; color: {{ $receptionTimeColor }};">
                                    {{ $receptionTimeValue }}
                                </p>
                            @endif
                            @if($data['image'])
                                <img src="{{ url('uploads/' . $data['image']) }}" class="mx-auto mb-4 max-w-xs rounded-lg shadow-md object-contain">
                            @endif
                            @if($data['location_name'])
                                <p class="font-semibold mb-2" style="font-family: '{{ $receptionContentFontFamily }}', serif; font-size: {{ $receptionContentFontSize }}; color: {{ $receptionContentColor }};">
                                    {{ $data['location_name'] }}
                                </p>
                            @endif
                            @if($data['address'])
                                <p class="mb-4" style="font-family: '{{ $receptionContentFontFamily }}', serif; font-size: {{ $receptionContentFontSize }}; color: {{ $receptionContentColor }};">
                                    {{ $data['address'] }}
                                </p>
                            @endif
                            @if($data['map_link'])
                                <a
                                    href="{{ $data['map_link'] }}"
                                    target="_blank"
                                    class="inline-block px-6 py-2 rounded-full hover:opacity-90 transition"
                                    style="font-family: '{{ $receptionButtonFontFamily }}', serif; font-size: {{ $receptionButtonFontSize }}; color: {{ $receptionButtonTextColor }}; background-color: {{ $receptionButtonBackgroundColor }};"
                                >
                                    {{ $data['button_text'] ?? 'Ver mapa' }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- BLOCK: DRESS CODE --}}
                    @if($type === 'dress_code')
                        @php
                            $titleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $titleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $contentFontFamily = $data['content_font_family'] ?? $fontFamily;
                            $contentTextSize = $data['content_text_size'] ?? $textSize;
                            $titleColor = $data['title_color'] ?? $textColor;
                            $contentColor = $data['content_color'] ?? $textColor;
                            $subtitleFontFamily = $data['subtitle_font_family'] ?? $contentFontFamily;
                            $subtitleTextSize = $data['subtitle_text_size'] ?? $contentTextSize;
                            $subtitleColor = $data['subtitle_color'] ?? $contentColor;
                            $iconSize = $data['icon_size'] ?? 'h-16 w-16';
                            $iconSizePxMap = [
                                'h-12 w-12' => '64px',
                                'h-16 w-16' => '96px',
                                'h-20 w-20' => '128px',
                                'h-24 w-24' => '160px',
                            ];
                            $iconSizePx = $iconSizePxMap[$iconSize] ?? '96px';
                            $titleFontSize = $sizeScale[$titleTextSize] ?? $sizeScale['text-xl'];
                            $contentFontSize = $sizeScale[$contentTextSize] ?? $sizeScale['text-lg'];
                            $subtitleFontSize = $sizeScale[$subtitleTextSize] ?? $sizeScale['text-lg'];
                        @endphp
                        <div class="text-center max-w-2xl mx-auto">
                            @if(isset($data['image']) && $data['image'])
                                <img src="{{ url('uploads/' . $data['image']) }}" class="mx-auto mb-4 object-contain" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};">
                            @else
                                <x-icon name="{{ $data['icon'] ?? 'heroicon-o-user' }}" class="mx-auto mb-4 opacity-80" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};" />
                            @endif
                            
                            <h4 class="font-bold mb-2 uppercase tracking-wide" style="font-family: '{{ $titleFontFamily }}', serif; font-size: {{ $titleFontSize }}; color: {{ $titleColor }};">
                                {{ $data['title'] }}
                            </h4>
                            @if(!empty($data['subtitle']))
                                <p class="mb-4" style="font-family: '{{ $subtitleFontFamily }}', serif; font-size: {{ $subtitleFontSize }}; color: {{ $subtitleColor }};">
                                    {{ $data['subtitle'] }}
                                </p>
                            @endif
                            <p class="whitespace-pre-line" style="font-family: '{{ $contentFontFamily }}', serif; font-size: {{ $contentFontSize }}; color: {{ $contentColor }};">
                                {{ $data['content'] }}
                            </p>
                        </div>
                    @endif

                    {{-- BLOCK: GIFT TABLE --}}
                    @if($type === 'gift_table')
                        @php
                            $titleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $titleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $descriptionFontFamily = $data['description_font_family'] ?? $fontFamily;
                            $descriptionTextSize = $data['description_text_size'] ?? $textSize;
                            $linkFontFamily = $data['link_font_family'] ?? $fontFamily;
                            $linkTextSize = $data['link_text_size'] ?? $textSize;
                            $titleColor = $data['title_color'] ?? $textColor;
                            $descriptionColor = $data['description_color'] ?? $textColor;
                            $linkColor = $data['link_color'] ?? $textColor;
                            $iconSize = $data['icon_size'] ?? 'h-16 w-16';
                            $iconSizePxMap = [
                                'h-12 w-12' => '64px',
                                'h-16 w-16' => '96px',
                                'h-20 w-20' => '128px',
                                'h-24 w-24' => '160px',
                            ];
                            $iconSizePx = $iconSizePxMap[$iconSize] ?? '96px';
                            $titleFontSize = $sizeScale[$titleTextSize] ?? $sizeScale['text-xl'];
                            $descriptionFontSize = $sizeScale[$descriptionTextSize] ?? $sizeScale['text-lg'];
                            $linkFontSize = $sizeScale[$linkTextSize] ?? $sizeScale['text-lg'];
                        @endphp
                        <div class="text-center max-w-2xl mx-auto">
                            @if(isset($data['image']) && $data['image'])
                                <img src="{{ url('uploads/' . $data['image']) }}" class="mx-auto mb-4 object-contain" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};">
                            @else
                                <x-icon name="{{ $data['icon'] ?? 'heroicon-o-gift' }}" class="mx-auto mb-4 opacity-80" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};" />
                            @endif
                            
                            <h4 class="font-bold mb-4 uppercase tracking-wide" style="font-family: '{{ $titleFontFamily }}', serif; font-size: {{ $titleFontSize }}; color: {{ $titleColor }};">
                                {{ $data['title'] }}
                            </h4>
                            @if(!empty($data['description']))
                                <p class="mb-4" style="font-family: '{{ $descriptionFontFamily }}', serif; font-size: {{ $descriptionFontSize }}; color: {{ $descriptionColor }};">
                                    {{ $data['description'] }}
                                </p>
                            @endif
                            @if(!empty($data['link_image']))
                                @if(!empty($data['link']))
                                    <a href="{{ $data['link'] }}" target="_blank" class="inline-block">
                                        <img src="{{ url('uploads/' . $data['link_image']) }}" class="mx-auto mb-4 max-w-xs rounded-lg shadow-md object-contain">
                                    </a>
                                @else
                                    <img src="{{ url('uploads/' . $data['link_image']) }}" class="mx-auto mb-4 max-w-xs rounded-lg shadow-md object-contain">
                                @endif
                            @elseif(!empty($data['link']))
                                <a href="{{ $data['link'] }}" target="_blank" class="hover:underline font-semibold" style="font-family: '{{ $linkFontFamily }}', serif; font-size: {{ $linkFontSize }}; color: {{ $linkColor }};">
                                    {{ $data['link_text'] }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- BLOCK: INSTAGRAM --}}
                    @if($type === 'instagram')
                        @php
                            $titleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $titleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $hashtagFontFamily = $data['hashtag_font_family'] ?? $fontFamily;
                            $hashtagTextSize = $data['hashtag_text_size'] ?? 'text-2xl';
                            $titleColor = $data['title_color'] ?? $textColor;
                            $hashtagColor = $data['hashtag_color'] ?? $textColor;
                            $iconSize = $data['icon_size'] ?? 'h-16 w-16';
                            $iconSizePxMap = [
                                'h-12 w-12' => '64px',
                                'h-16 w-16' => '96px',
                                'h-20 w-20' => '128px',
                                'h-24 w-24' => '160px',
                                'h-32 w-32' => '200px',
                            ];
                            $iconSizePx = $iconSizePxMap[$iconSize] ?? '96px';
                            $titleFontSize = $sizeScale[$titleTextSize] ?? $sizeScale['text-xl'];
                            $hashtagFontSize = $sizeScale[$hashtagTextSize] ?? $sizeScale['text-xl'];
                        @endphp
                        <div class="text-center max-w-2xl mx-auto">
                            @if(isset($data['image']) && $data['image'])
                                <img src="{{ url('uploads/' . $data['image']) }}" class="mx-auto mb-4 object-contain" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};">
                            @else
                                <x-icon name="{{ $data['icon'] ?? 'heroicon-o-camera' }}" class="mx-auto mb-4 opacity-80" style="width: {{ $iconSizePx }}; height: {{ $iconSizePx }};" />
                            @endif
                            
                            <h4 class="font-bold mb-4 uppercase tracking-wide" style="font-family: '{{ $titleFontFamily }}', serif; font-size: {{ $titleFontSize }}; color: {{ $titleColor }};">
                                {{ $data['title'] }}
                            </h4>
                            <p class="font-bold" style="font-family: '{{ $hashtagFontFamily }}', serif; font-size: {{ $hashtagFontSize }}; color: {{ $hashtagColor }};">
                                {{ $data['hashtag'] }}
                            </p>
                        </div>
                    @endif

                    {{-- BLOCK: CUSTOM CONTENT --}}
                    @if($type === 'custom_content')
                        @php
                            $mediaWidthKey = $data['media_width'] ?? 'max-w-lg';
                            $mediaWidthMap = [
                                'max-w-sm' => '320px',
                                'max-w-md' => '480px',
                                'max-w-lg' => '640px',
                                'max-w-xl' => '800px',
                                'max-w-2xl' => '960px',
                                'full' => '100%',
                            ];
                            $mediaMaxWidth = $mediaWidthMap[$mediaWidthKey] ?? '640px';
                            $mediaFullBleed = (bool) ($data['media_full_bleed'] ?? false);
                            // Use CSS calc to break out of container padding reliably
                            $mediaWrapperClass = $mediaFullBleed ? '' : 'mx-auto';
                            $mediaWrapperStyle = $mediaFullBleed
                                ? 'width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);'
                                : 'max-width:'.$mediaMaxWidth.';';
                            $mediaStyle = 'width:100%;height:auto;';
                            $mediaRadiusShadowClass = $mediaFullBleed ? '' : 'rounded-lg shadow-md';
                        @endphp
                        <div class="text-center max-w-4xl mx-auto {{ $textSize }}" style="color: {{ $textColor }}">
                            @if(isset($data['image']) && $data['image'])
                                <div class="mb-8 {{ $mediaWrapperClass }}" style="{{ $mediaWrapperStyle }}">
                                    <img src="{{ url('uploads/' . $data['image']) }}" class="object-contain {{ $mediaRadiusShadowClass }}" style="{{ $mediaStyle }}">
                                </div>
                            @endif
                            <div class="prose mx-auto">
                                {!! $data['content'] !!}
                            </div>
                            @if(isset($data['video']) && $data['video'])
                                <div class="mt-8 {{ $mediaWrapperClass }}" style="{{ $mediaWrapperStyle }}">
                                    <video class="{{ $mediaRadiusShadowClass }}" controls playsinline style="{{ $mediaStyle }}">
                                        <source src="{{ url('uploads/' . $data['video']) }}">
                                    </video>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- BLOCK: RSVP --}}
                    @if($type === 'rsvp')
                        @php
                            $rsvpTitleFontFamily = $data['title_font_family'] ?? $fontFamily;
                            $rsvpTitleTextSize = $data['title_text_size'] ?? 'text-2xl';
                            $rsvpTitleColor = $data['title_color'] ?? $textColor;
                            $rsvpDescriptionFontFamily = $data['description_font_family'] ?? $fontFamily;
                            $rsvpDescriptionTextSize = $data['description_text_size'] ?? $textSize;
                            $rsvpDescriptionColor = $data['description_color'] ?? $textColor;
                            $rsvpFormFontFamily = $data['form_font_family'] ?? $fontFamily;
                            $rsvpFormTextSize = $data['form_text_size'] ?? 'text-base';
                            $rsvpFormTextColor = $data['form_text_color'] ?? '#111827';
                            $rsvpInputBorderColor = $data['input_border_color'] ?? '#d1d5db';
                            $rsvpButtonFontFamily = $data['button_font_family'] ?? $fontFamily;
                            $rsvpButtonTextSize = $data['button_text_size'] ?? 'text-base';
                            $rsvpButtonTextColor = $data['button_text_color'] ?? '#ffffff';
                            $rsvpButtonBackgroundColor = $data['button_background_color'] ?? '#2563eb';
                            $rsvpWhatsappButtonTextColor = $data['whatsapp_button_text_color'] ?? '#ffffff';
                            $rsvpWhatsappButtonBackgroundColor = $data['whatsapp_button_background_color'] ?? '#22c55e';
                            $rsvpFormWidthKey = $data['form_width'] ?? 'md';
                            $rsvpFormWidthMap = [
                                'sm' => '320px',
                                'md' => '480px',
                                'lg' => '640px',
                                'xl' => '800px',
                                'full' => '100%',
                            ];
                            $rsvpFormMaxWidth = $rsvpFormWidthMap[$rsvpFormWidthKey] ?? '480px';
                            $rsvpTitleFontSize = $sizeScale[$rsvpTitleTextSize] ?? $sizeScale['text-xl'];
                            $rsvpDescriptionFontSize = $sizeScale[$rsvpDescriptionTextSize] ?? $sizeScale['text-lg'];
                            $rsvpFormFontSize = $sizeScale[$rsvpFormTextSize] ?? '1rem';
                            $rsvpButtonFontSize = $sizeScale[$rsvpButtonTextSize] ?? '1rem';
                        @endphp
                        <div class="text-center mx-auto {{ $textSize }}" style="max-width: {{ $rsvpFormMaxWidth }};">
                            <h2 class="font-bold mb-8" style="font-family: '{{ $rsvpTitleFontFamily }}', serif; font-size: {{ $rsvpTitleFontSize }}; color: {{ $rsvpTitleColor }};">
                                {{ $data['title'] }}
                            </h2>
                            <p class="mb-8" style="font-family: '{{ $rsvpDescriptionFontFamily }}', serif; font-size: {{ $rsvpDescriptionFontSize }}; color: {{ $rsvpDescriptionColor }};">
                                {{ $data['description'] }}
                            </p>

                            <div class="mx-auto text-left">
                                <form action="{{ route('invitation.rsvp', $invitation->slug) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-left mb-2" style="font-family: '{{ $rsvpFormFontFamily }}', serif; font-size: {{ $rsvpFormFontSize }}; color: {{ $rsvpFormTextColor }};">
                                            Nombre Completo
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            required
                                            class="w-full rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-0"
                                            style="border: 1px solid {{ $rsvpInputBorderColor }}; font-family: '{{ $rsvpFormFontFamily }}', serif; font-size: {{ $rsvpFormFontSize }}; color: {{ $rsvpFormTextColor }};"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-left mb-2" style="font-family: '{{ $rsvpFormFontFamily }}', serif; font-size: {{ $rsvpFormFontSize }}; color: {{ $rsvpFormTextColor }};">
                                            Mensaje para confirmar
                                        </label>
                                        <textarea
                                            name="message"
                                            rows="3"
                                            class="w-full rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-0"
                                            style="border: 1px solid {{ $rsvpInputBorderColor }}; font-family: '{{ $rsvpFormFontFamily }}', serif; font-size: {{ $rsvpFormFontSize }}; color: {{ $rsvpFormTextColor }};"
                                        ></textarea>
                                    </div>
                                    <button
                                        type="submit"
                                        class="w-full rounded hover:opacity-90 transition"
                                        style="font-family: '{{ $rsvpButtonFontFamily }}', serif; font-size: {{ $rsvpButtonFontSize }}; color: {{ $rsvpButtonTextColor }}; background-color: {{ $rsvpButtonBackgroundColor }}; padding-top: 0.75rem; padding-bottom: 0.75rem;"
                                    >
                                        Enviar Confirmación
                                    </button>
                                </form>

                                @if($invitation->whatsapp_number)
                                    <a
                                        href="https://wa.me/{{ $invitation->whatsapp_number }}?text=Hola,%20quiero%20confirmar%20mi%20asistencia%20al%20evento%20{{ $invitation->category->name }}"
                                        target="_blank"
                                        class="mt-4 inline-flex justify-center w-full rounded-full hover:opacity-90 transition shadow-lg"
                                        style="font-family: '{{ $rsvpButtonFontFamily }}', serif; font-size: {{ $rsvpButtonFontSize }}; color: {{ $rsvpWhatsappButtonTextColor }}; background-color: {{ $rsvpWhatsappButtonBackgroundColor }}; padding-top: 0.75rem; padding-bottom: 0.75rem;"
                                    >
                                        Confirmar por WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        @endforeach
    @else
        <!-- Fallback for invitations created before the builder update (Old Layout) -->
        <!-- Dedication -->
        @if($invitation->dedication)
        <section class="py-20 dedication-section w-full">
            <div class="container mx-auto px-4 text-center max-w-4xl">
                <h2 class="text-3xl font-bold mb-8">Nuestra Historia</h2>
                <p class="text-lg leading-relaxed">{{ $invitation->dedication }}</p>
            </div>
        </section>
        @endif
        
        <!-- ... (Rest of the old static layout would go here if we wanted perfect backward compatibility, 
             but for now let's encourage using the new builder) ... -->
             <div class="text-center py-20">
                <p class="text-gray-500">Esta invitación usa el formato antiguo. Por favor actualízala en el panel administrativo.</p>
             </div>
    @endif

    <footer class="py-8 bg-black text-white text-center text-sm">
        <p>&copy; {{ date('Y') }} Invitatorio. Hecho con ❤️</p>
    </footer>

    @if($invitation->event_date)
    <script>
        const eventDate = new Date("{{ $invitation->event_date->format('Y-m-d H:i:s') }}").getTime();

        const countdown = setInterval(function() {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                clearInterval(countdown);
                document.getElementById("countdown").innerHTML = "¡El gran día ha llegado!";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = `
                <div class="countdown-item"><span class="countdown-number">${days}</span><span class="countdown-label">Días</span></div>
                <div class="countdown-item"><span class="countdown-number">${hours}</span><span class="countdown-label">Hs</span></div>
                <div class="countdown-item"><span class="countdown-number">${minutes}</span><span class="countdown-label">Min</span></div>
                <div class="countdown-item"><span class="countdown-number">${seconds}</span><span class="countdown-label">Seg</span></div>
            `;
        }, 1000);
    </script>
    @endif
    <script>
        (function () {
            const els = document.querySelectorAll('.anim');
            if (!('IntersectionObserver' in window) || els.length === 0) {
                els.forEach(el => el.classList.add('is-in'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-in');
                        const once = true;
                        if (once) {
                            io.unobserve(entry.target);
                        }
                    }
                });
            }, { threshold: 0.15 });
            els.forEach(el => {
                const duration = el.getAttribute('data-duration');
                const delay = el.getAttribute('data-delay');
                if (duration) el.style.transitionDuration = duration + 'ms';
                if (delay) el.style.transitionDelay = delay + 'ms';
                io.observe(el);
            });
        })();
    </script>
</body>
</html>
