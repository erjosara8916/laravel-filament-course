<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

use Filament\Schemas\Components\Tabs\Tab;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                Tabs::make("Tabs")
                    ->tabs([
                        Tab::make("Personal")
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required(),
                                DateTimePicker::make('email_verified_at')
                                    ->hidden(true),
                                TextInput::make('password')
                                    ->password()
                                    ->required(),
                                Textarea::make('two_factor_secret')
                                    ->hidden(true)
                                    ->columnSpanFull(),
                                Textarea::make('two_factor_recovery_codes')
                                    ->hidden(true)
                                    ->columnSpanFull(),
                                DateTimePicker::make('two_factor_confirmed_at')
                                    ->hidden(true),
                            ]),
                        Tab::make('Direccion')
                            ->schema([
                                Select::make('country_id')
                                    ->label('Pais')
                                    ->relationship('country', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('state_id', null);
                                        $set('city_id', null);
                                    })
                                    ->required(),
                                Select::make('state_id')
                                    ->label('Departamento')
                                    ->options(fn(Get $get): Collection => State::query()
                                        ->where('country_id', $get('country_id'))
                                        ->where('is_active', true)
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('city_id', null);
                                    })
                                    ->required(),
                                Select::make('city_id')
                                    ->label('Ciudad')
                                    ->options(fn(Get $get): Collection => City::query()
                                        ->where('state_id', $get('state_id'))
                                        ->where('is_active', true)
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ])
                    ])
            );
    }
}
