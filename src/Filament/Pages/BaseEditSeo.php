<?php

namespace Vvb13a\FilamentModelSeo\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSeoCanonicalUrlSchema;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSeoDescriptionSchema;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSeoKeywordsSchema;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSeoRobotsSchema;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSeoTitleSchema;
use Vvb13a\FilamentModelSeo\Filament\Pages\Traits\HasSlugSection;

class BaseEditSeo extends EditRecord
{
    use HasSeoTitleSchema;
    use HasSeoDescriptionSchema;
    use HasSeoCanonicalUrlSchema;
    use HasSeoKeywordsSchema;
    use HasSeoRobotsSchema;
    use HasSlugSection;

    protected static ?string $navigationIcon = 'heroicon-o-signal';
    protected static ?string $navigationLabel = 'SEO';

    public function form(Form $form): Form
    {
        return $form->schema($this->getSeoFormSchema());
    }

    /**
     * Returns the complete schema array for the form.
     * Determines the overall layout (e.g., main grid vs. sidebar).
     */
    protected function getSeoFormSchema(): array
    {
        $schema = [];

        // 1. Add the Slug Section (Bound to the main model)
        if (method_exists($this, 'getSlugSection')) {
            $schema[] = $this->getSlugSection();
        }

        // 2. Add SEO Sections Grid (Bound to 'seoData' relationship)
        $schema[] = Grid::make()
            ->relationship('seoData')
            ->schema($this->getSeoSectionsSchema())
            ->columns(1);

        return $schema;
    }

    /**
     * Returns the schema array for the SEO sections grid.
     * This is where individual SEO sections/fields are assembled.
     */
    protected function getSeoSectionsSchema(): array
    {
        return array_filter([
            $this->getSeoTitleSection(),
            $this->getSeoDescriptionSection(),
            $this->getSeoCanonicalUrlSection(),
            $this->getSeoKeywordsSection(),
            $this->getSeoRobotsSection()
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->action(function ($livewire): void {
                    $livewire->save();
                })
                ->submit(null)
                ->label('Save SEO')
                ->color('success')
                ->icon('heroicon-o-bookmark-square')
                ->iconPosition(IconPosition::After)
                ->keyBindings(['mod+s'])
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
