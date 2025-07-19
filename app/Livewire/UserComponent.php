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

    public $id, $name, $email, $phone_number, $password, $search;

    // Reset pagination ketika search berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage user';
        
        if (!empty($this->search)) {
            $data['users'] = User::where('name', 'like', '%' . $this->search . '%')->paginate(5);
        } else {
            $data['users'] = User::paginate(5);
        }
        
        return view('livewire.user-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => bcrypt($this->password), 
            'role' => 'admin'
        ]);

        session()->flash('success', 'Admin has been saved successfully');
        $this->reset(['name', 'email', 'phone_number', 'password']);
    }

    public function update($id)
    {
        $user = User::find($id);
        
        if ($user) {
            $this->id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone_number = $user->phone_number;
            $this->password = '';
        }
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $user = User::find($this->id);
        
        if ($user) {
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
            ];
            
            // Only update password if provided
            if (!empty($this->password)) {
                $updateData['password'] = bcrypt($this->password);
            }
            
            $user->update($updateData);

            session()->flash('success', 'Admin has been updated successfully');
            $this->reset(['id', 'name', 'email', 'phone_number', 'password']);
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