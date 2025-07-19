<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class MemberComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $name, $address, $email, $phone_number, $password, $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage Member';

        $query = User::where('role', 'member');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $data['members'] = $query->paginate(5);

        return view('livewire.member-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'address' => $this->address,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => bcrypt($this->password),
            'role' => 'member',
        ]);

        session()->flash('success', 'Member successfully added.');
        $this->reset(['name', 'address', 'email', 'phone_number', 'password']);
    }

    public function edit($id)
    {
        $member = User::find($id);

        if ($member) {
            $this->id = $member->id;
            $this->name = $member->name;
            $this->address = $member->address;
            $this->email = $member->email;
            $this->phone_number = $member->phone_number;
            $this->password = '';
        }
    }

    public function update($id)
    {
        $member = User::find($id);

        if ($member) {
            $this->id = $member->id;
            $this->name = $member->name;
            $this->address = $member->address;
            $this->email = $member->email;
            $this->phone_number = $member->phone_number;
            $this->password = $member->password;
        }
    }

    public function updateMember()
    {
        $this->validate([
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'email' => 'email',
            'phone_number' => 'string|max:20',
            'password' => 'min:6',
        ]);

        $member = User::find($this->id);

        if ($member) {
            $updateData = [
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'password' => $this->password,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = bcrypt($this->password);
            }

            $member->update($updateData);

            session()->flash('success', 'Member has been updated successfully.');
            $this->reset(['id', 'name', 'address', 'email', 'phone_number', 'password']);
        }
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $member = User::find($this->id);

        if ($member) {
            $member->delete();
            session()->flash('success', 'Member successfully deleted.');
            $this->reset('id');
        }
    }
}
