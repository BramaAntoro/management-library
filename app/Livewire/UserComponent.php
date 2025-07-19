<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $name, $email, $password, $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage user';

        $query = User::where('role', 'admin');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $data['users'] = $query->paginate(5);

        return view('livewire.user-component',$data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => 'admin'
        ]);

        session()->flash('success', 'Admin has been saved successfully');
        $this->reset(['name', 'email', 'password']);
    }

    public function update($id)
    {
        $user = User::find($id);

        if ($user) {
            $this->id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = $user->password;
        }
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $this->id,
            'password' => 'nullable|min:6',
        ]);

        $user = User::find($this->id);

        if ($user) {
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = bcrypt($this->password);
            }

            $user->update($updateData);

            session()->flash('success', 'Admin has been updated successfully');
            $this->reset(['id', 'name', 'email', 'password']);
        }
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $user = User::find($this->id);

        if ($user) {
            $user->delete();
            session()->flash('success', 'Admin has been deleted successfully');
            $this->reset('id');
        }
    }
}