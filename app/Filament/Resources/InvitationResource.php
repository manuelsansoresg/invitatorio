<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvitationResource\Pages;
use App\Models\Invitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class InvitationResource extends Resource
{
    protected static ?string $model = Invitation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getBaseDesignSchema(): array
    {
        return [
            Forms\Components\ColorPicker::make('background_color')
                ->label('Color de Fondo')
                ->default('#ffffff'),
            Forms\Components\FileUpload::make('background_image')
                ->label('Imagen de Fondo (Opcional)')
                ->image()
                ->disk('public_uploads')
                ->directory('invitations/backgrounds')
                ->visibility('public'),
            Forms\Components\Select::make('padding_y')
                ->label('Espaciado Vertical')
                ->options([
                    'py-8' => 'Pequeño',
                    'py-16' => 'Normal',
                    'py-24' => 'Grande',
                    'py-32' => 'Extra Grande',
                ])
                ->default('py-16'),
        ];
    }

    public static function getTypographySchema(): array
    {
        return [
            Forms\Components\Select::make('font_family')
                ->label('Tipografía')
                ->options(self::getFontOptions())
                ->default('Playfair Display'),
            Forms\Components\Select::make('text_size')
                ->label('Tamaño de Texto')
                ->options(self::getSizeOptions())
                ->default('text-lg'),
        ];
    }

    public static function getSizeOptions(): array
    {
        return [
            'text-base' => 'Normal',
            'text-lg' => 'Mediano',
            'text-xl' => 'Grande',
            'text-2xl' => 'Extra grande',
        ];
    }

    public static function getIconSizeOptions(): array
    {
        return [
            'h-12 w-12' => 'Normal',
            'h-16 w-16' => 'Mediano',
            'h-20 w-20' => 'Grande',
            'h-24 w-24' => 'Extra grande',
        ];
    }

    public static function mapSizeToRem(?string $size): string
    {
        $scale = [
            'text-base' => '1.25rem',
            'text-lg' => '1.5rem',
            'text-xl' => '2.4rem',
            'text-2xl' => '3.6rem',
        ];

        return $scale[$size] ?? $scale['text-lg'];
    }

    public static function makeTypographyPreview(string $name, string $fontField, string $sizeField, ?string $colorField = null, string $label = 'Vista previa', string $sample = 'Aa Bb Cc 123'): Forms\Components\Placeholder
    {
        return Forms\Components\Placeholder::make($name)
            ->label($label)
            ->content(function (Get $get) use ($fontField, $sizeField, $colorField, $sample): HtmlString {
                $fontFamily = $get($fontField) ?: 'Playfair Display';
                $sizeKey = $get($sizeField) ?: 'text-lg';
                $color = $colorField ? ($get($colorField) ?: '#111827') : '#111827';
                $fontSize = self::mapSizeToRem($sizeKey);

                return new HtmlString(
                    '<div style="padding:0.25rem 0; font-family:\''.e($fontFamily).'\', serif; font-size:'.e($fontSize).'; color:'.e($color).';">'.
                    e($sample).
                    '</div>'
                );
            })
            ->reactive()
            ->columnSpanFull();
    }

    public static function getFontOptions(): array
    {
        return [
            'Playfair Display' => 'Playfair Display (Serif)',
            'Lato' => 'Lato (Sans)',
            'Montserrat' => 'Montserrat',
            'Great Vibes' => 'Great Vibes (Cursiva)',
            'Dancing Script' => 'Dancing Script (Cursiva)',
            'Allura' => 'Allura (Cursiva fina)',
            'Alex Brush' => 'Alex Brush (Cursiva elegante)',
            'Pacifico' => 'Pacifico (Cursiva llamativa)',
            'Satisfy' => 'Satisfy (Cursiva suave)',
            'Parisienne' => 'Parisienne (Cursiva elegante)',
            'Yellowtail' => 'Yellowtail (Cursiva dinámica)',
            'Italianno' => 'Italianno (Cursiva estilizada)',
            'Tangerine' => 'Tangerine (Cursiva muy decorativa)',
            'Courgette' => 'Courgette (Cursiva legible)',
            'Marck Script' => 'Marck Script (Cursiva manuscrita)',
        ];
    }

    public static function getDesignSchema(): array
    {
        return array_merge(
            self::getBaseDesignSchema(),
            self::getTypographySchema(),
        );
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Diseño y Personalización')
                    ->description('Personaliza la cabecera principal de la invitación.')
                    ->schema([
                        Forms\Components\Tabs::make('Secciones')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('General')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('design_settings.hero.text_color')
                                            ->label('Color de Texto (Hero)')
                                            ->default('#ffffff'),
                                        Forms\Components\Select::make('design_settings.hero.font_family')
                                            ->label('Tipografía Principal')
                                            ->options(self::getFontOptions())
                                            ->default('Playfair Display'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make('Estética')
                    ->description('Personaliza la apariencia de la invitación.')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_photo_path')
                            ->image()
                            ->disk('public_uploads')
                            ->directory('invitations/covers')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('background_music_path')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav'])
                            ->disk('public_uploads')
                            ->directory('invitations/music')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make('Contenido de la Invitación')
                    ->description('Construye tu invitación añadiendo bloques en el orden que prefieras.')
                    ->schema([
                        Forms\Components\Builder::make('content_blocks')
                            ->label('Bloques de Contenido')
                            ->blocks([
                                Forms\Components\Builder\Block::make('dedication')
                                    ->label('Nuestra Historia')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Título')
                                                            ->default('Nuestra Historia'),
                                                        Forms\Components\Textarea::make('content')
                                                            ->label('Texto')
                                                            ->rows(4),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Título')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'dedication_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Título de ejemplo'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Texto')
                                                                ->schema([
                                                                    Forms\Components\Select::make('body_font_family')
                                                                        ->label('Tipografía Texto')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('body_text_size')
                                                                        ->label('Tamaño Texto')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('body_color')
                                                                        ->label('Color Texto')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'dedication_body_preview',
                                                                        'body_font_family',
                                                                        'body_text_size',
                                                                        'body_color',
                                                                        'Vista previa texto',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('ceremony')
                                    ->label('Ceremonia')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('location_name')
                                                            ->label('Nombre del Lugar'),
                                                        Forms\Components\TextInput::make('address')
                                                            ->label('Dirección'),
                                                        Forms\Components\TimePicker::make('time')
                                                            ->label('Hora'),
                                                        Forms\Components\TextInput::make('map_link')
                                                            ->label('Link de Mapa')
                                                            ->url(),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Icono')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->options([
                                                                'heroicon-o-home' => 'Casa / Templo',
                                                                'heroicon-o-heart' => 'Corazón',
                                                            ])
                                                            ->default('heroicon-o-home'),
                                                        Forms\Components\Select::make('icon_size')
                                                            ->label('Tamaño del Icono')
                                                            ->options(self::getIconSizeOptions())
                                                            ->default('h-16 w-16'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen Personalizada')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/icons')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Tarjeta')
                                                                ->schema([
                                                                    Forms\Components\ColorPicker::make('card_background_color')
                                                                        ->label('Color de Fondo Tarjeta')
                                                                        ->default('#ffffff'),
                                                                    Forms\Components\FileUpload::make('card_background_image')
                                                                        ->label('Imagen de Fondo Tarjeta (Opcional)')
                                                                        ->image()
                                                                        ->disk('public_uploads')
                                                                        ->directory('invitations/backgrounds')
                                                                        ->visibility('public'),
                                                                    Forms\Components\ColorPicker::make('card_text_color')
                                                                        ->label('Color de Texto Tarjeta')
                                                                        ->default('#1f2937'),
                                                                    Forms\Components\ColorPicker::make('card_border_color')
                                                                        ->label('Color de Borde Tarjeta')
                                                                        ->default('#e5e7eb'),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Tipografía')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'ceremony_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Título de ejemplo'
                                                                    ),
                                                                    Forms\Components\Select::make('content_font_family')
                                                                        ->label('Tipografía Contenido')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('content_text_size')
                                                                        ->label('Tamaño Contenido')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('content_color')
                                                                        ->label('Color Contenido')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'ceremony_content_preview',
                                                                        'content_font_family',
                                                                        'content_text_size',
                                                                        'content_color',
                                                                        'Vista previa contenido',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('reception')
                                    ->label('Recepción')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('location_name')
                                                            ->label('Nombre del Lugar'),
                                                        Forms\Components\TextInput::make('address')
                                                            ->label('Dirección'),
                                                        Forms\Components\TimePicker::make('time')
                                                            ->label('Hora'),
                                                        Forms\Components\TextInput::make('map_link')
                                                            ->label('Link de Mapa')
                                                            ->url(),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Icono')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->options([
                                                                'heroicon-o-musical-note' => 'Música',
                                                                'heroicon-o-cake' => 'Pastel',
                                                            ])
                                                            ->default('heroicon-o-musical-note'),
                                                        Forms\Components\Select::make('icon_size')
                                                            ->label('Tamaño del Icono')
                                                            ->options(self::getIconSizeOptions())
                                                            ->default('h-16 w-16'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen Personalizada')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/icons')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Tarjeta')
                                                                ->schema([
                                                                    Forms\Components\ColorPicker::make('card_background_color')
                                                                        ->label('Color de Fondo Tarjeta')
                                                                        ->default('#ffffff'),
                                                                    Forms\Components\FileUpload::make('card_background_image')
                                                                        ->label('Imagen de Fondo Tarjeta (Opcional)')
                                                                        ->image()
                                                                        ->disk('public_uploads')
                                                                        ->directory('invitations/backgrounds')
                                                                        ->visibility('public'),
                                                                    Forms\Components\ColorPicker::make('card_text_color')
                                                                        ->label('Color de Texto Tarjeta')
                                                                        ->default('#1f2937'),
                                                                    Forms\Components\ColorPicker::make('card_border_color')
                                                                        ->label('Color de Borde Tarjeta')
                                                                        ->default('#e5e7eb'),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Tipografía')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'reception_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Título de ejemplo'
                                                                    ),
                                                                    Forms\Components\Select::make('content_font_family')
                                                                        ->label('Tipografía Contenido')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('content_text_size')
                                                                        ->label('Tamaño Contenido')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('content_color')
                                                                        ->label('Color Contenido')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'reception_content_preview',
                                                                        'content_font_family',
                                                                        'content_text_size',
                                                                        'content_color',
                                                                        'Vista previa contenido',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('dress_code')
                                    ->label('Código de Vestimenta')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Título')
                                                            ->default('Código de Vestimenta'),
                                                        Forms\Components\Textarea::make('content')
                                                            ->label('Descripción')
                                                            ->rows(3),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Icono')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->options([
                                                                'heroicon-o-user' => 'Persona',
                                                                'heroicon-o-sparkles' => 'Elegante',
                                                            ])
                                                            ->default('heroicon-o-user'),
                                                        Forms\Components\Select::make('icon_size')
                                                            ->label('Tamaño del Icono')
                                                            ->options(self::getIconSizeOptions())
                                                            ->default('h-16 w-16'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen Personalizada')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/icons')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Título')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'dress_code_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Título de ejemplo'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Descripción')
                                                                ->schema([
                                                                    Forms\Components\Select::make('content_font_family')
                                                                        ->label('Tipografía Descripción')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('content_text_size')
                                                                        ->label('Tamaño Descripción')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('content_color')
                                                                        ->label('Color Descripción')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'dress_code_content_preview',
                                                                        'content_font_family',
                                                                        'content_text_size',
                                                                        'content_color',
                                                                        'Vista previa descripción',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('gift_table')
                                    ->label('Mesa de Regalos')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Título')
                                                            ->default('Mesa de Regalos'),
                                                        Forms\Components\Textarea::make('description')
                                                            ->label('Descripción')
                                                            ->rows(3),
                                                        Forms\Components\FileUpload::make('link_image')
                                                            ->label('Imagen de la Mesa')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/gifts')
                                                            ->visibility('public'),
                                                        Forms\Components\TextInput::make('link')
                                                            ->label('Link')
                                                            ->url(),
                                                        Forms\Components\TextInput::make('link_text')
                                                            ->label('Texto del Link')
                                                            ->default('Ver detalles'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Icono')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->options([
                                                                'heroicon-o-gift' => 'Regalo',
                                                            ])
                                                            ->default('heroicon-o-gift'),
                                                        Forms\Components\Select::make('icon_size')
                                                            ->label('Tamaño del Icono')
                                                            ->options(self::getIconSizeOptions())
                                                            ->default('h-16 w-16'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen Personalizada')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/icons')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Título')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'gift_table_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Título de ejemplo'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Descripción')
                                                                ->schema([
                                                                    Forms\Components\Select::make('description_font_family')
                                                                        ->label('Tipografía Descripción')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('description_text_size')
                                                                        ->label('Tamaño Descripción')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('description_color')
                                                                        ->label('Color Descripción')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'gift_table_description_preview',
                                                                        'description_font_family',
                                                                        'description_text_size',
                                                                        'description_color',
                                                                        'Vista previa descripción',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Link')
                                                                ->schema([
                                                                    Forms\Components\Select::make('link_font_family')
                                                                        ->label('Tipografía Link')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('link_text_size')
                                                                        ->label('Tamaño Link')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('link_color')
                                                                        ->label('Color Link')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'gift_table_link_preview',
                                                                        'link_font_family',
                                                                        'link_text_size',
                                                                        'link_color',
                                                                        'Vista previa link',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('instagram')
                                    ->label('Instagram / Hashtags')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Título')
                                                            ->default('Comparte con Nosotros'),
                                                        Forms\Components\TextInput::make('hashtag')
                                                            ->label('Hashtag'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Icono')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->options([
                                                                'heroicon-o-camera' => 'Cámara',
                                                            ])
                                                            ->default('heroicon-o-camera'),
                                                        Forms\Components\Select::make('icon_size')
                                                            ->label('Tamaño del Icono')
                                                            ->options(self::getIconSizeOptions())
                                                            ->default('h-16 w-16'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen Personalizada')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/icons')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Título')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'instagram_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Comparte con Nosotros'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Hashtag')
                                                                ->schema([
                                                                    Forms\Components\Select::make('hashtag_font_family')
                                                                        ->label('Tipografía Hashtag')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('hashtag_text_size')
                                                                        ->label('Tamaño Hashtag')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('hashtag_color')
                                                                        ->label('Color Hashtag')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'instagram_hashtag_preview',
                                                                        'hashtag_font_family',
                                                                        'hashtag_text_size',
                                                                        'hashtag_color',
                                                                        'Vista previa hashtag',
                                                                        '#hashtag'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('custom_content')
                                    ->label('Bloque Personalizado (Texto/Imagen/Video)')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\RichEditor::make('content')
                                                            ->label('Contenido Libre'),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Imagen (Opcional)')
                                                            ->image()
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/custom')
                                                            ->visibility('public'),
                                                        Forms\Components\FileUpload::make('video')
                                                            ->label('Video (Opcional)')
                                                            ->acceptedFileTypes([
                                                                'video/mp4',
                                                                'video/quicktime',
                                                                'video/webm',
                                                                'video/ogg',
                                                            ])
                                                            ->disk('public_uploads')
                                                            ->directory('invitations/videos')
                                                            ->visibility('public'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(self::getDesignSchema()),
                                            ]),
                                    ]),

                                Forms\Components\Builder\Block::make('rsvp')
                                    ->label('Confirmación de Asistencia')
                                    ->schema([
                                        Forms\Components\Tabs::make('Configuración')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Contenido')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Título')
                                                            ->default('Confirmación de Asistencia'),
                                                        Forms\Components\Textarea::make('description')
                                                            ->label('Descripción')
                                                            ->default('Esperamos contar con tu presencia en este día tan especial.'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Diseño')
                                                    ->schema(array_merge(
                                                        self::getBaseDesignSchema(),
                                                        [
                                                            Forms\Components\Fieldset::make('Título')
                                                                ->schema([
                                                                    Forms\Components\Select::make('title_font_family')
                                                                        ->label('Tipografía Título')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Playfair Display')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('title_text_size')
                                                                        ->label('Tamaño Título')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-2xl')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('title_color')
                                                                        ->label('Color Título')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'rsvp_title_preview',
                                                                        'title_font_family',
                                                                        'title_text_size',
                                                                        'title_color',
                                                                        'Vista previa título',
                                                                        'Confirmación de Asistencia'
                                                                    ),
                                                                ]),
                                                            Forms\Components\Fieldset::make('Descripción')
                                                                ->schema([
                                                                    Forms\Components\Select::make('description_font_family')
                                                                        ->label('Tipografía Descripción')
                                                                        ->options(self::getFontOptions())
                                                                        ->default('Lato')
                                                                        ->live(),
                                                                    Forms\Components\Select::make('description_text_size')
                                                                        ->label('Tamaño Descripción')
                                                                        ->options(self::getSizeOptions())
                                                                        ->default('text-lg')
                                                                        ->live(),
                                                                    Forms\Components\ColorPicker::make('description_color')
                                                                        ->label('Color Descripción')
                                                                        ->default(null)
                                                                        ->live(),
                                                                    self::makeTypographyPreview(
                                                                        'rsvp_description_preview',
                                                                        'description_font_family',
                                                                        'description_text_size',
                                                                        'description_color',
                                                                        'Vista previa descripción',
                                                                        'Texto de ejemplo'
                                                                    ),
                                                                ]),
                                                        ]
                                                    )),
                                            ]),
                                    ]),
                            ])
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Evento (Datos Base)')
                    ->schema([
                        Forms\Components\DateTimePicker::make('event_date'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_photo_path')
                    ->disk('public_uploads')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Invitation $record): string => route('invitation.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvitations::route('/'),
            'create' => Pages\CreateInvitation::route('/create'),
            'edit' => Pages\EditInvitation::route('/{record}/edit'),
        ];
    }
}
