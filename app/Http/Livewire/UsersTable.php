<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Exceptions\DataTableConfigurationException;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    public $onlyUnaccepted = false;

    protected $model = User::class;

    public function mount($onlyUnaccepted = false)
    {
        $this->onlyUnaccepted = $onlyUnaccepted;
    }

    public function builder(): Builder
    {
        $q = User::query()
            ->with('roles', 'schools');

        if ($this->onlyUnaccepted) {
            $q->whereNull('accepted_at');
            $school_ids = getSchoolsFromUser(\Auth::user())->toArray();
            $q->whereHas('schools', function ($query) use ($school_ids) {
                $query->whereIn('schools.id', $school_ids);
            });

        } else {
            $q->whereNotNull('accepted_at');
        }

        return $q;
    }

    /**
     * @throws DataTableConfigurationException
     */
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSearchDebounce(300);
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Id", "id")
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('users.id', 'ilike', '%'.$searchTerm.'%'))
                ->sortable(),
            Column::make('Nome', 'name')
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('users.name', 'ilike', '%'.$searchTerm.'%'))

                ->sortable(),
            Column::make('Cognome', 'surname')
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('users.surname', 'ilike', '%'.$searchTerm.'%'))

                ->sortable(),
            Column::make('Email')
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhere('users.email', 'ilike', '%'.$searchTerm.'%'))
                ->sortable(),
        ];

        if ($this->onlyUnaccepted) {

            $columns[] = Column::make("Chiede di iscriversi come:", "id")
                ->format(fn($value, User $row, Column $column) => $row->firstRoleString());
            $columns[] = Column::make("Azioni", 'id')
                ->format(fn($value, $row, Column $column) => view('livewire.livewire-tables.users-unaccepted-actions')->with('name', $row->name)->with('id', $value));

        } else {
            $columns[] = Column::make('Ruoli')
                ->label(fn($row) => $row->roles->pluck('name')->implode(', ') ?? 'Nessuno')
                ->sortable()
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhereHas('roles', function ($query) use ($searchTerm) {
                    $query->where('name', 'ILIKE', '%' . $searchTerm . '%');
                }));
            $columns[] = Column::make('Scuole Associate')
                ->label(fn($row) => $row->schools->pluck('name')->implode(', ') ?? 'Nessuna')
                ->searchable(fn(Builder $query, $searchTerm) => $query->orWhereHas('schools', function ($query) use ($searchTerm) {
                    $query->where('name', 'ILIKE', '%' . $searchTerm . '%');
                }))
                ->sortable();
            $columns[] = Column::make("Azioni", 'id')
                ->format(fn($value, $row, Column $column) => view('livewire.livewire-tables.users-actions')->with('name', $row->name)->with('id', $value));
        }

        return $columns;
    }


    public function deleteUser($id)
    {
        User::find($id)->delete();
    }
}
