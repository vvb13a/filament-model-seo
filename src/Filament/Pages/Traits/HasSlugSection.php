<?php

namespace Vvb13a\FilamentModelSeo\Filament\Pages\Traits;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

trait HasSlugSection
{
    /**
     * Configuration: The database field name for the slug.
     */
    protected string $slugFieldName = 'slug';

    /**
     * Configuration: Minimum recommended length for the slug.
     */
    protected int $slugMinLength = 10;

    /**
     * Configuration: Maximum recommended length for the slug.
     */
    protected int $slugMaxLength = 60;

    /**
     * Configuration: Is the slug field required?
     */
    protected bool $isSlugRequired = true;

    /**
     * Configuration: Is the slug field length enforced?
     */
    protected bool $isSlugLengthEnforced = false;

    /**
     * Returns the Section component containing the Slug field.
     *
     * Override this method to customize the section structure or return null to disable.
     */
    protected function getSlugSection(): ?Component
    {
        return Section::make('URL Slug')
            ->aside()
            ->description('This defines the unique web address segment for this record. It should be descriptive and SEO-friendly.')
            ->schema([
                $this->getSlugField(),
            ]);
    }

    /**
     * Returns the TextInput component for the Slug.
     * Override this method to customize the field itself.
     */
    protected function getSlugField(): Component
    {
        return TextInput::make($this->slugFieldName)
            ->label('URL Slug')
            ->helperText('The unique identifying part of the URL. Use lowercase letters, numbers, and hyphens.')
            ->lazy()
            ->afterStateUpdated(function ($state, Set $set) {
                $set('slug', str($state)->slug());
            })
            ->minLength($this->isSlugLengthEnforced ? $this->slugMinLength : null)
            ->maxLength($this->isSlugLengthEnforced ? $this->slugMaxLength : null)
            ->hint(
                view('filament-model-seo::length-indicator')
                    ->with([
                        'minLength' => $this->slugMinLength,
                        'maxLength' => $this->slugMaxLength,
                        'attr' => 'data.slug',
                    ])
            )
            ->required($this->isSlugRequired)
            ->prefix(
                str($this->getRecord()->seoTest->getCanonicalUrl())
                    ->replaceEnd('/', '')
                    ->beforeLast('/')
                    ->append('/')
            );
    }
}
