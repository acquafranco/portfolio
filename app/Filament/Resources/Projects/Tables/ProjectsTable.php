<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use App\Portfolio\ProjectStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->description('Arrastrá las filas (botón de ordenar) para cambiar el orden en el sitio.')
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->getStateUsing(fn (Project $record): ?string => Project::mediaUrl($record->cover_small_path ?? $record->cover_path))
                    ->imageWidth(96)
                    ->imageHeight(50),
                TextColumn::make('name')
                    ->label('Proyecto')
                    ->weight('semibold')
                    ->description(fn (Project $record): ?string => $record->content['es']['category'] ?? null)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state): string => __('portfolio.status.'.$state->value, [], 'es'))
                    ->color(fn (ProjectStatus $state): string => match ($state) {
                        ProjectStatus::Production => 'success',
                        ProjectStatus::Development => 'warning',
                        ProjectStatus::Experimental => 'info',
                    }),
                IconColumn::make('is_featured')->label('Destacado')->boolean(),
                ToggleColumn::make('is_published')->label('Publicado'),
                TextColumn::make('url')
                    ->label('Sitio')
                    ->formatStateUsing(fn (?string $state): ?string => $state ? parse_url($state, PHP_URL_HOST) : null)
                    ->url(fn (Project $record): ?string => $record->url, shouldOpenInNewTab: true)
                    ->color('gray'),
                TextColumn::make('updated_at')->label('Actualizado')->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(ProjectStatus::cases())->mapWithKeys(fn (ProjectStatus $status): array => [
                        $status->value => __('portfolio.status.'.$status->value, [], 'es'),
                    ])->all()),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Ver en el sitio')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Project $record): string => route('projects.show', ['locale' => config('portfolio.default_locale'), 'slug' => $record->slug]), shouldOpenInNewTab: true)
                    ->visible(fn (Project $record): bool => $record->is_published),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
