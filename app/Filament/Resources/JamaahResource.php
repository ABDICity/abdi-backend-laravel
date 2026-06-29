<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\JamaahResource\Pages;
use App\Filament\Resources\JamaahResource\RelationManagers;
use App\Models\Jamaah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JamaahResource extends Resource
{
    protected static ?string $model = Jamaah::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Manajemen Jamaah';

    protected static ?string $pluralModelLabel = 'Data Jamaah 4B';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Formulir Integrasi Jamaah ABDI')
                    ->tabs([
                        // TAB 1: IDENTITAS UTAMA
                        Forms\Components\Tabs\Tab::make('Profil & Wilayah')
                            ->icon('heroicon-m-identification')
                            ->schema([
                                Forms\Components\Select::make('koordinator_id')
                                    ->relationship('koordinator', 'name')
                                    ->label('Koordinator Wilayah / Agen Pembina')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('wilayah_dakwah')
                                    ->label('Cakupan Wilayah Gerakan')
                                    ->required(),
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nomor_whatsapp')
                                    ->required()
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                                Forms\Components\Textarea::make('alamat_domisili')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 2: MATRIKS KAFFAH (4B)
                        Forms\Components\Tabs\Tab::make('Matriks Kaffah (4B)')
                            ->icon('heroicon-m-shield-check')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('status_keanggotaan')
                                            ->options([
                                                'Santri' => 'Santri',
                                                'Kader' => 'Kader',
                                                'Da\'i' => 'Da\'i',
                                                'Murobbi' => 'Murobbi',
                                            ])->required(),
                                        Forms\Components\Select::make('status_aktif')
                                            ->options([
                                                'Aktif' => 'Aktif',
                                                'Pasif' => 'Pasif',
                                                'Butuh Pendampingan' => 'Butuh Pendampingan',
                                            ])->required(),
                                    ]),

                                Forms\Components\Section::make('1. Pilar Berdakwah & Berjamaah')
                                    ->schema([
                                        Forms\Components\TextInput::make('skor_edukasi_dakwah')->numeric()->default(0),
                                        Forms\Components\TextInput::make('jumlah_binaan_jamaah')->numeric()->default(0),
                                        Forms\Components\TextInput::make('kehadiran_halaqah_count')->numeric()->default(0),
                                    ])->columns(3),

                                Forms\Components\Section::make('2. Pilar Bersyariah & Bermuamalah')
                                    ->schema([
                                        Forms\Components\Select::make('kondisi_ruhiyah')
                                            ->options(['Prima' => 'Prima', 'Stabil' => 'Stabil', 'Futur' => 'Futur'])->required(),
                                        Forms\Components\Toggle::make('is_clear_syariah_dasar')->inline(false),
                                        Forms\Components\TextInput::make('total_kontribusi_infak')->numeric()->prefix('Rp')->default(0),
                                        Forms\Components\Select::make('status_kemandirian_ekonomi')
                                            ->options(['Muzakki' => 'Muzakki', 'Munfiq' => 'Munfiq', 'Mustahik' => 'Mustahik'])->required(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('wilayah_dakwah')->label('Wilayah')->searchable(),
                Tables\Columns\TextColumn::make('status_keanggotaan')->badge()->color('info'),
                Tables\Columns\TextColumn::make('status_aktif')->badge()->color('success'),
                Tables\Columns\TextColumn::make('total_kontribusi_infak')->label('Infak')->money('IDR'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_keanggotaan'),
            ])
            ->actions([
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
            // Relasi tambahan bisa ditempatkan di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJamaahs::route('/'),
            'create' => Pages\CreateJamaah::route('/create'),
            'edit' => Pages\EditJamaah::route('/{record}/edit'),
        ];
    }
}
