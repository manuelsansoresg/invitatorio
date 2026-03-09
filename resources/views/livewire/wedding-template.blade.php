<div class="wedding-template-wrapper font-sans text-gray-700 antialiased">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            color: #4a5568;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        .script-font {
            font-family: 'Great Vibes', cursive;
        }
        
        /* Dynamic Colors */
        .bg-primary { background-color: {{ $primaryColor }}; }
        .text-primary { color: {{ $primaryColor }}; }
        .border-primary { border-color: {{ $primaryColor }}; }
        
        .bg-secondary { background-color: {{ $secondaryColor }}; }
        .text-secondary { color: {{ $secondaryColor }}; }
        
        .btn-primary {
            background-color: {{ $primaryColor }};
            color: white;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>

    {{-- Admin Panel --}}
    @if($isAdmin)
        <div x-data="{ open: @entangle('showEditor') }" 
             class="fixed top-0 right-0 h-full z-50 flex shadow-2xl"
             style="max-width: 400px; width: 100%;">
            
            {{-- Toggle Button --}}
            <button @click="open = !open" 
                    class="absolute top-4 left-0 -ml-12 bg-white p-3 rounded-l-md shadow-md text-gray-600 hover:text-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>

            {{-- Panel Content --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-full bg-white h-full overflow-y-auto border-l border-gray-200 flex flex-col">
                
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-[#E9DCC9]/20">
                    <h2 class="text-lg font-serif font-bold text-gray-800">Personalizar página</h2>
                    <div class="flex items-center space-x-2">
                         <button wire:click="$refresh" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-8 flex-1">
                    
                    <!-- Theme Selection -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Bandera (Tema)</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['Lino', 'Medianoche', 'Sabio', 'Bebé azul'] as $t)
                                <button wire:click="$set('theme', '{{ $t }}')" 
                                        class="flex items-center justify-between px-3 py-2 border rounded-full text-sm transition {{ $theme === $t ? 'border-gray-500 bg-gray-50 ring-1 ring-gray-500' : 'border-gray-200 hover:border-gray-300' }}">
                                    <span class="text-gray-700">{{ $t }}</span>
                                    <div class="w-4 h-4 rounded-full border border-gray-200" 
                                         style="background-color: 
                                            @if($t == 'Lino') #E5E0D8 
                                            @elseif($t == 'Medianoche') #2d3748 
                                            @elseif($t == 'Sabio') #cbd5e0 
                                            @else #bee3f8 @endif">
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Colors -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Colores Personalizados</label>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Primario</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" wire:model.live="primaryColor" class="h-10 w-10 rounded border border-gray-300 cursor-pointer p-1">
                                    <input type="text" wire:model.live="primaryColor" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Secundario</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" wire:model.live="secondaryColor" class="h-10 w-10 rounded border border-gray-300 cursor-pointer p-1">
                                    <input type="text" wire:model.live="secondaryColor" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map -->
                    <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Mapa (URL)</label>
                         <textarea wire:model.live="mapUrl" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs font-mono"></textarea>
                         <p class="text-[10px] text-gray-400 mt-1">Pega el código 'src' del iframe de Google Maps.</p>
                    </div>

                    <!-- Text Content -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Texto Principal</label>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Título (Pareja)</label>
                                <input type="text" wire:model.live="title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Subtítulo</label>
                                <input type="text" wire:model.live="subtitle" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Fecha (Texto)</label>
                                <input type="text" wire:model.live="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Fecha del Evento (Countdown)</label>
                                <input type="datetime-local" wire:model.blur="eventDate" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quote -->
                    <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Cita</label>
                         <textarea wire:model.live="quoteText" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                         <input type="text" wire:model.live="quoteAuthor" placeholder="Autor" class="mt-2 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <!-- Story -->
                     <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Historia</label>
                         <textarea wire:model.live="storyText" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>

                </div>
                
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                     <button wire:click="save" wire:loading.attr="disabled" class="w-full bg-black text-white py-3 rounded-md font-semibold hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center">
                         <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                         <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                         </span>
                     </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="w-full mx-auto bg-white shadow-2xl overflow-hidden" style="max-width: 800px;">
        
        {{-- Hero Section --}}
    <header class="relative text-white text-center py-24 px-6 flex flex-col justify-center items-center min-h-[600px] bg-cover bg-center group" 
            style="background-color: {{ $primaryColor }}; background-blend-mode: multiply; background-image: url('{{ is_string($heroImage) ? $heroImage : ($heroImage ? $heroImage->temporaryUrl() : '') }}');">
        
        @if($isAdmin)
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                <label for="hero-upload" class="cursor-pointer">
                    <div class="w-40 h-40 rounded-full bg-gray-500/80 backdrop-blur-sm flex flex-col items-center justify-center text-white hover:bg-gray-600/90 transition shadow-xl border-4 border-white/20">
                        <span class="text-[10px] font-bold uppercase tracking-widest mb-2">Clic para subir foto</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                    <input type="file" id="hero-upload" wire:model.live="heroImage" class="hidden" accept="image/*">
                </label>
            </div>
        @endif

        <div class="relative z-10 animate-fade-in-down">
                
                <h2 class="text-xl uppercase tracking-widest mb-4 font-light">{{ $subtitle }}</h2>
                <h1 class="text-6xl md:text-8xl script-font mb-6 leading-tight">{{ $title }}</h1>
                
                <div class="border-t border-b border-white/40 py-2 inline-block px-8 mt-4">
                    <p class="text-xl uppercase tracking-widest font-serif">{{ $date }}</p>
                </div>
                
                <div class="mt-12">
                     <button class="px-8 py-3 border border-white text-white uppercase tracking-widest hover:bg-white hover:text-gray-900 transition duration-300 text-sm">
                         {{ $locationButtonText }}
                     </button>
                </div>
            </div>
        </header>

        {{-- Story Section --}}
        <section class="py-20 px-6 md:px-12 text-center bg-white">
            <h2 class="text-4xl font-serif text-gray-800 mb-8 uppercase tracking-wide">{{ $storyTitle }}</h2>
            <p class="max-w-2xl mx-auto text-gray-600 leading-relaxed mb-16 italic font-serif text-lg">
                "{{ $storyText }}"
            </p>

            <div class="grid md:grid-cols-3 gap-8 items-center max-w-6xl mx-auto relative">
                {{-- Left: Bride (Hidden on mobile, shown first on mobile via order or separate block) --}}
                <div class="text-center hidden md:block relative z-10">
                    <h3 class="text-3xl font-serif text-gray-800 mb-4">{{ $storyBrideName }}</h3>
                    <p class="text-gray-600 leading-relaxed italic">
                        {{ $storyBrideText }}
                    </p>
                </div>

                {{-- Center: Image + Ampersand --}}
                <div class="relative group flex justify-center items-center">
                    {{-- Ampersand Background --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none select-none">
                         <span class="text-[300px] font-serif text-gray-300">&</span>
                    </div>

                    <div class="w-48 h-48 md:w-60 md:h-60 rounded-full overflow-hidden shadow-xl border-4 border-white bg-gray-200 relative z-10 flex-shrink-0 aspect-square" style="border-radius: 50%;">
                        {{-- Placeholder for Couple Image --}}
                        <img src="{{ is_string($storyImage) ? $storyImage : ($storyImage ? $storyImage->temporaryUrl() : '') }}" alt="Couple" class="w-full h-full object-cover rounded-full aspect-square" style="border-radius: 50%;">
                        
                        @if($isAdmin)
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 rounded-full aspect-square" style="border-radius: 50%;">
                                <label for="story-upload" class="cursor-pointer">
                                    <div class="w-32 h-32 rounded-full bg-gray-500/80 backdrop-blur-sm flex flex-col items-center justify-center text-white hover:bg-gray-600/90 transition shadow-xl border-2 border-white/20">
                                        <span class="text-[8px] font-bold uppercase tracking-widest mb-1 text-center px-2">Clic para subir foto</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    </div>
                                    <input type="file" id="story-upload" wire:model.live="storyImage" class="hidden" accept="image/*">
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Mobile: Bride (Visible only on mobile) --}}
                <div class="text-center md:hidden relative z-10">
                    <h3 class="text-3xl font-serif text-gray-800 mb-4">{{ $storyBrideName }}</h3>
                    <p class="text-gray-600 leading-relaxed italic">
                        {{ $storyBrideText }}
                    </p>
                </div>

                {{-- Right: Groom --}}
                <div class="text-center relative z-10">
                    <h3 class="text-3xl font-serif text-gray-800 mb-4">{{ $storyGroomName }}</h3>
                    <p class="text-gray-600 leading-relaxed italic">
                        {{ $storyGroomText }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Video/Photo Banner / Carousel --}}
        <section class="h-96 relative group bg-gray-200" 
                 wire:key="carousel-{{ count($galleryImages) }}"
                 x-data="{ 
                    activeSlide: 0, 
                    total: {{ count($galleryImages) }},
                    timer: null,
                    init() {
                        this.startTimer();
                    },
                    startTimer() {
                        if (this.timer) clearInterval(this.timer);
                        if (this.total > 1) {
                            this.timer = setInterval(() => {
                                this.activeSlide = (this.activeSlide + 1) % this.total;
                            }, 3000);
                        }
                    }
                 }">
             
            {{-- Carousel Slides --}}
            @foreach($galleryImages as $index => $img)
                <div x-show="activeSlide === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 transform scale-105"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-1000"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ is_string($img) ? $img : $img->temporaryUrl() }}');">
                     <div class="absolute inset-0 bg-black/10"></div>
                </div>
            @endforeach
            
            {{-- Fallback for no images --}}
            @if(empty($galleryImages))
                <div class="absolute inset-0 flex items-center justify-center bg-gray-300">
                    <span class="text-gray-500 uppercase tracking-widest">Sin imágenes</span>
                </div>
            @endif

            {{-- Carousel Controls --}}
            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10" x-show="total > 1">
                @foreach($galleryImages as $index => $img)
                    <button @click="activeSlide = {{ $index }}; startTimer()" 
                            class="w-3 h-3 rounded-full transition-colors duration-300"
                            :class="activeSlide === {{ $index }} ? 'bg-white' : 'bg-white/50 hover:bg-white/80'">
                    </button>
                @endforeach
            </div>
            
            {{-- Admin Upload Overlay --}}
            @if($isAdmin)
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20 bg-black/20">
                     <label for="gallery-upload" class="cursor-pointer">
                         <div class="w-48 h-48 rounded-full bg-gray-500/80 backdrop-blur-sm flex flex-col items-center justify-center text-white hover:bg-gray-600/90 transition shadow-xl border-4 border-white/20 text-center p-4">
                             <span class="text-[10px] font-bold uppercase tracking-widest mb-2">Clic para subir hasta 4 fotos</span>
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                             </svg>
                         </div>
                         <input type="file" id="gallery-upload" wire:model.live="galleryImages" class="hidden" multiple accept="image/*">
                     </label>
                 </div>
            @endif
        </section>

        {{-- Quote Section --}}
        <section class="py-20 px-6 text-center">
             <div class="max-w-2xl mx-auto border border-[#E9DCC9] p-12 relative">
                 <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-white px-4">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#E9DCC9]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.01691 21L5.01691 18C5.01691 16.8954 5.91234 16 7.01691 16H10.0169C10.5692 16 11.0169 15.5523 11.0169 15V9C11.0169 8.44772 10.5692 8 10.0169 8H6.01691C5.46462 8 5.01691 8.44772 5.01691 9V11C5.01691 11.5523 4.56919 12 4.01691 12H3.01691V5H13.0169V15C13.0169 18.3137 10.3306 21 7.01691 21H5.01691Z" />
                    </svg>
                 </div>
                 <h3 class="text-2xl md:text-3xl font-serif text-gray-700 italic leading-relaxed mb-4">
                     {{ $quoteText }}
                 </h3>
                 <p class="text-sm uppercase tracking-widest text-gray-500">{{ $quoteAuthor }}</p>
             </div>
        </section>

        {{-- Countdown --}}
        <section class="py-10 bg-gray-50" 
                 wire:key="countdown-{{ $eventDate }}"
                 x-data="{
                     target: new Date('{{ $eventDate }}').getTime(),
                     days: '00', hours: '00', minutes: '00', seconds: '00',
                     init() {
                         this.update();
                         setInterval(() => this.update(), 1000);
                     },
                     update() {
                         const now = new Date().getTime();
                         const distance = this.target - now;
                         if (distance < 0) { 
                            this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                            return; 
                         }
                         this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                         this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                         this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                         this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
                     }
                 }">
            <div class="flex flex-wrap justify-center gap-6 md:gap-12 text-center">
                <div class="bg-white p-6 w-32 shadow-sm border-t-4" style="border-color: {{ $primaryColor }}">
                    <span class="block text-4xl font-serif" style="color: {{ $primaryColor }}" x-text="days">00</span>
                    <span class="text-xs uppercase tracking-widest text-gray-500">Días</span>
                </div>
                <div class="bg-white p-6 w-32 shadow-sm border-t-4" style="border-color: {{ $primaryColor }}">
                    <span class="block text-4xl font-serif" style="color: {{ $primaryColor }}" x-text="hours">00</span>
                    <span class="text-xs uppercase tracking-widest text-gray-500">Horas</span>
                </div>
                <div class="bg-white p-6 w-32 shadow-sm border-t-4" style="border-color: {{ $primaryColor }}">
                    <span class="block text-4xl font-serif" style="color: {{ $primaryColor }}" x-text="minutes">00</span>
                    <span class="text-xs uppercase tracking-widest text-gray-500">Minutos</span>
                </div>
                <div class="bg-white p-6 w-32 shadow-sm border-t-4" style="border-color: {{ $primaryColor }}">
                    <span class="block text-4xl font-serif" style="color: {{ $primaryColor }}" x-text="seconds">00</span>
                    <span class="text-xs uppercase tracking-widest text-gray-500">Segundos</span>
                </div>
            </div>
        </section>

        {{-- Events --}}
        <section class="py-20 px-6">
            <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
                {{-- Ceremony --}}
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 text-[#d0cbc5]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-widest text-[#d0cbc5] mb-2">{{ $ceremonyTitle }}</h3>
                    <p class="text-gray-600 mb-1">{{ $ceremonyTime }}</p>
                    <p class="text-gray-600 uppercase">{{ $ceremonyLocation }}</p>
                    <button class="mt-4 text-xs font-bold uppercase tracking-widest border-b border-gray-400 pb-1 hover:text-black hover:border-black transition">Ver en Mapa</button>
                </div>

                {{-- Reception --}}
                <div class="text-center">
                     <div class="w-20 h-20 mx-auto mb-6 text-[#d0cbc5]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-widest text-[#d0cbc5] mb-2">{{ $receptionTitle }}</h3>
                    <p class="text-gray-600 mb-1">{{ $receptionTime }}</p>
                    <p class="text-gray-600 uppercase">{{ $receptionLocation }}</p>
                     <button class="mt-4 text-xs font-bold uppercase tracking-widest border-b border-gray-400 pb-1 hover:text-black hover:border-black transition">Ver en Mapa</button>
                </div>
            </div>
        </section>

        {{-- Registry/Brands --}}
        <section class="py-16 bg-[#F9F7F2] text-center">
            <h3 class="text-4xl font-serif text-[#E9DCC9] mb-12">34</h3> {{-- Placeholder number from image --}}
            <div class="flex flex-wrap justify-center items-center gap-12 opacity-60 grayscale hover:grayscale-0 transition duration-500">
                <span class="text-3xl font-black text-gray-400">SEARS</span>
                <span class="text-3xl font-serif italic text-gray-400">Palacio de Hierro</span>
                <span class="text-3xl font-sans text-gray-400">Liverpool</span>
            </div>
        </section>

        {{-- Map --}}
        <section class="h-96 w-full relative">
            <iframe src="{{ $mapUrl }}" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </section>

    </main>

    @if(session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="fixed top-4 right-4 z-[60] bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif
</div>
