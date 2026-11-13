<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Portfolio\ProjectKind;
use App\Portfolio\ProjectStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Proyecto')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(80)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state, string $operation): void {
                                        if ($operation === 'create' && blank($get('slug'))) {
                                            $set('slug', Str::slug((string) $state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->helperText('Se usa en /es/projects/{slug}. Solo minúsculas, números y guiones.')
                                    ->required()
                                    ->maxLength(80)
                                    ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                                    ->unique(ignoreRecord: true),
                                TextInput::make('url')
                                    ->label('Sitio en producción')
                                    ->url()
                                    ->placeholder('https://mi-producto.com')
                                    ->maxLength(255),
                                TextInput::make('repository')
                                    ->label('Repositorio público (GitHub)')
                                    ->url()
                                    ->placeholder('https://github.com/acquafranco/...')
                                    ->helperText('Dejalo vacío si el código es privado.')
                                    ->maxLength(255),
                            ]),

                        Section::make('Descripción')
                            ->description('El español es obligatorio. Lo que dejes vacío en portugués o inglés se muestra en español.')
                            ->schema([
                                Tabs::make('Idiomas')
                                    ->tabs(collect(config('portfolio.locales'))
                                        ->map(fn (array $locale, string $code): Tab => self::contentTab($code, $locale['label']))
                                        ->values()
                                        ->all()),
                            ]),

                        Section::make('Enlaces extra')
                            ->description('Opcional: documentación, demo, video, etc.')
                            ->collapsed()
                            ->schema([
                                Repeater::make('links')
                                    ->hiddenLabel()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Agregar enlace')
                                    ->schema([
                                        TextInput::make('label')->label('Texto')->required()->maxLength(40),
                                        TextInput::make('url')->label('URL')->url()->required(),
                                    ]),
                            ]),
                    ]),

                Grid::make(1)
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Publicación')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publicado')
                                    ->helperText('Si está apagado no aparece en el sitio.')
                                    ->default(true),
                                Toggle::make('is_featured')
                                    ->label('Destacado')
                                    ->helperText('Tarjeta grande en la home.')
                                    ->default(true),
                                Select::make('kind')
                                    ->label('Tipo')
                                    ->helperText('Los proyectos para clientes muestran la etiqueta "Proyecto para cliente".')
                                    ->options(collect(ProjectKind::cases())->mapWithKeys(fn (ProjectKind $kind): array => [
                                        $kind->value => __('portfolio.projects.kind_'.$kind->value, [], 'es'),
                                    ])->all())
                                    ->default(ProjectKind::Product->value)
                                    ->required()
                                    ->native(false),
                                Select::make('status')
                                    ->label('Estado')
                                    ->options(collect(ProjectStatus::cases())->mapWithKeys(fn (ProjectStatus $status): array => [
                                        $status->value => __('portfolio.status.'.$status->value, [], 'es'),
                                    ])->all())
                                    ->default(ProjectStatus::Development->value)
                                    ->required()
                                    ->native(false),
                                TextInput::make('year')
                                    ->label('Año')
                                    ->numeric()
                                    ->minValue(2000)
                                    ->maxValue(2100)
                                    ->default((int) now()->format('Y')),
                                ColorPicker::make('accent')
                                    ->label('Color del proyecto')
                                    ->helperText('Brillo, bordes y detalles de su tarjeta.')
                                    ->regex('/^#[0-9A-Fa-f]{6}$/')
                                    ->default('#7C8CFF')
                                    ->required(),
                            ]),

                        Section::make('Imágenes')
                            ->schema([
                                FileUpload::make('cover_path')
                                    ->label('Captura')
                                    ->helperText('Screenshot del producto. Se recorta a 1200×630 desde arriba y se convierte a WebP.')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('projects/uploads')
                                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                                    ->maxSize(8192)
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['40:21', null]),
                                FileUpload::make('logo_path')
                                    ->label('Logo / ícono')
                                    ->helperText('Cuadrado. Se reduce a 160×160 WebP.')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('projects/uploads')
                                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'])
                                    ->maxSize(4096)
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['1:1']),
                            ]),

                        Section::make('Tecnologías')
                            ->schema([
                                TagsInput::make('stack')
                                    ->hiddenLabel()
                                    ->placeholder('Laravel, MySQL… (Enter para agregar)')
                                    ->reorderable()
                                    ->default([]),
                            ]),
                    ]),
            ]);
    }

    private static function contentTab(string $code, string $label): Tab
    {
        $required = $code === config('portfolio.default_locale');
        $field = fn (string $name): string => "content.{$code}.{$name}";

        return Tab::make($label)
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make($field('category'))
                        ->label('Categoría')
                        ->placeholder('SaaS · Gestión de …')
                        ->required($required)
                        ->maxLength(80),
                    TextInput::make($field('tagline'))
                        ->label('Frase principal')
                        ->placeholder('Una frase que resuma el producto.')
                        ->required($required)
                        ->maxLength(140),
                ]),
                Textarea::make($field('summary'))
                    ->label('Resumen')
                    ->helperText('Qué es y para quién. Aparece en la tarjeta y en Google.')
                    ->required($required)
                    ->rows(3)
                    ->maxLength(500),
                Grid::make(2)->schema([
                    Textarea::make($field('problem'))->label('El problema')->required($required)->rows(4)->maxLength(700),
                    Textarea::make($field('solution'))->label('La solución')->required($required)->rows(4)->maxLength(700),
                ]),
                Repeater::make($field('features'))
                    ->label('Funcionalidades')
                    ->helperText('Las primeras 4 se muestran en la home; todas en la página del proyecto.')
                    ->simple(TextInput::make('feature')->required()->maxLength(140))
                    ->reorderable()
                    ->defaultItems($required ? 1 : 0)
                    ->addActionLabel('Agregar funcionalidad'),
                Grid::make(2)->schema([
                    Textarea::make($field('audience'))->label('Para quién es')->rows(3)->maxLength(400),
                    Textarea::make($field('role'))->label('Mi rol')->rows(3)->maxLength(400),
                ]),
                Grid::make(2)->schema([
                    Textarea::make($field('testimonial'))
                        ->label('Testimonio del cliente')
                        ->helperText('Solo opiniones reales, con permiso del cliente. Si queda vacío no se muestra nada.')
                        ->rows(3)
                        ->maxLength(400),
                    TextInput::make($field('testimonial_author'))
                        ->label('Quién lo dice')
                        ->placeholder('Nombre · Cargo, Empresa')
                        ->maxLength(120),
                ]),
            ]);
    }
}
