<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $label = 'Level Pengguna';
    protected static ?string $navigationGroup = 'Kelola Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Level')
                    ->placeholder('Masukkan Nama Level Pengguna')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Level')
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
                        // Pastikan bahwa user tidak bisa menghapus role dirinya sendiri
                        return Auth::id() !== $record->id; // Jika ini untuk role, ganti dengan kondisi role
                    })
                    ->using(function ($record) {
                        // Pastikan role yang sedang dipilih bukan role pengguna yang sedang login
                        if (Auth::user()->hasRole($record->name)) {
                            session()->flash('error', 'You cannot delete your own role.');
                            return false; // Mencegah penghapusan role
                        }

                        $record->delete(); // Lanjutkan penghapusan role jika bukan role yang sedang login
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function ($records) {
                            // Filter keluar role yang digunakan oleh pengguna yang sedang login
                            $rolesToDelete = $records->reject(function ($record) {
                                return Auth::user()->hasRole($record->name); // Cek apakah role tersebut dimiliki oleh pengguna yang login
                            });

                            // Lakukan penghapusan role yang tidak terkait dengan pengguna yang sedang login
                            $rolesToDelete->each(function ($record) {
                                $record->delete();
                            });

                            // Pesan sukses
                            session()->flash('message', 'Selected roles were deleted, except for your own role.');
                        })
                        ->requiresConfirmation(),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
