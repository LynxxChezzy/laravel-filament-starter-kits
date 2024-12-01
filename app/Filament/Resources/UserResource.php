<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->placeholder('Masukkan Nama Pengguna')
                    ->minLength(3)
                    ->maxLength(255)
                    ->required(),

                TextInput::make('email')
                    ->label('Email Pengguna')
                    ->placeholder('Masukkan Email Pengguna')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->label(fn($context) => $context === 'create' ? 'Password Pengguna' : 'Ubah Password')
                    ->placeholder(fn($context) => $context === 'create' ? 'Masukkan Password Pengguna' : 'Kosongkan jika tidak ingin mengubah password')
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)),


                Select::make('roles')
                    ->label('Level Pengguna')
                    ->placeholder('Pilih Level Pengguna')
                    ->relationship('roles', 'name')
                    ->native(false)
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
                    ->label('Pengguna')
                    ->formatStateUsing(function (User $record) {
                        $nameParts = explode(' ', trim($record->name));
                        $initials = isset($nameParts[1])
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($nameParts[0], 0, 1));
                        $avatarUrl = $record->avatar_url
                            ? asset('storage/' . $record->avatar_url)
                            : 'https://ui-avatars.com/api/?name=' . $initials . '&background=000000&color=ffffff&size=128';
                        $image = '<img src="' . $avatarUrl . '" alt="Avatar User" class="w-10 h-10 rounded-lg mr-2.5">';
                        $nama = '<strong>' . e($record->name) . '</strong>';
                        $email = e($record->email);
                        return '<div class="flex items-center">'
                            . $image
                            . '<div>' . $nama . '<br>' . $email . '</div></div>';
                    })
                    ->html()
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
