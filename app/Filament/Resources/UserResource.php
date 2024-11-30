<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Pengguna';
    protected static ?string $navigationGroup = 'Kelola Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->placeholder('Masukkan Nama Pengguna')
                    ->minLength(3)
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email Pengguna')
                    ->placeholder('Masukkan Email Pengguna')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label(fn($context) => $context === 'create' ? 'Password Pengguna' : 'Ubah Password') // Kondisi untuk label
                    ->placeholder(fn($context) => $context === 'create' ? 'Masukkan Password Pengguna' : 'Kosongkan jika tidak ingin mengubah password') // Kondisi untuk placeholder
                    ->password()
                    ->required(fn(string $context) => $context === 'create') // Wajib hanya pada pembuatan
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)),


                Forms\Components\Select::make('roles')
                    ->label('Level Pengguna')
                    ->placeholder('Pilih Level Pengguna')
                    ->relationship('roles', 'name') // Menggunakan relationship dari Spatie
                    ->native(false) // Karena pengguna dapat memiliki banyak peran
                    ->preload()
                    ->searchable()
                    ->required(),

                FileUpload::make('avatar_url')
                    ->label('Photo Profil')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->imageCropAspectRatio('1:1')
                    ->circleCropper()
                    ->directory('avatar_upload')
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email Pengguna')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->authorize(function ($record) {
                        // Pastikan bahwa user tidak bisa menghapus dirinya sendiri
                        return Auth::id() !== $record->id;
                    })
                    ->using(function ($record) {
                        if ($record->id === Auth::id()) {
                            session()->flash('error', 'You cannot delete your own account.');
                            return false; // Prevent deletion
                        }

                        $record->delete(); // Proceed with deletion
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function ($records) {
                            // Filter out the logged-in user's record from the selected records
                            $recordsToDelete = $records->reject(function ($record) {
                                return $record->id === Auth::id(); // Prevent the deletion of the logged-in user's record
                            });

                            // Delete the filtered records
                            $recordsToDelete->each(function ($record) {
                                $record->delete();
                            });

                            // Optionally, you can add a success message
                            session()->flash('message', 'Selected accounts were deleted, except for your own account.');
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
