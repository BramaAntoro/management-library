<div class="card">
    <div class="card-header">
        Manage Member
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="search" class="form-control w-50 m-2" placeholder="Search data admin">

        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Process</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone_number }}</td>
                            <td>{{ $member->address }}</td>
                            <td>{{ $member->role }}</td>
                            <td>
                                <a href="#" wire:click.prevent="update({{ $member->id }})" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updatePage">Update</a>
                                <a href="#" wire:click.prevent="confirm({{ $member->id }})" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePage">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $members->links() }}

            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addPage">Add</a>
        </div>
    </div>

    <!-- Modal Add -->
    <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addLabel">Add Member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Name</label>
                            @error('name') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="name">
                        </div>
                        <div class="form-group mb-2">
                            <label>Email</label>
                            @error('email') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="email" class="form-control" wire:model="email">
                        </div>
                        <div class="form-group mb-2">
                            <label>Address</label>
                            @error('address') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="address">
                        </div>
                        <div class="form-group mb-2">
                            <label>Phone Number</label>
                            @error('phone_number') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="phone_number">
                        </div>
                        <div class="form-group mb-2">
                            <label>Password</label>
                            @error('password') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="password" class="form-control" wire:model="password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="store" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div wire:ignore.self class="modal fade" id="updatePage" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateLabel">Update Member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Name</label>
                            @error('name') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="name">
                        </div>
                        <div class="form-group mb-2">
                            <label>Email</label>
                            @error('email') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="email" class="form-control" wire:model="email">
                        </div>
                        <div class="form-group mb-2">
                            <label>Address</label>
                            @error('address') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="address">
                        </div>
                        <div class="form-group mb-2">
                            <label>Phone Number</label>
                            @error('phone_number') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="phone_number">
                        </div>
                        <div class="form-group mb-2">
                            <label>Password</label>
                            @error('password') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="password" class="form-control" wire:model="password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="updateMember" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div wire:ignore.self class="modal fade" id="deletePage" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteLabel">Delete Member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this member?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" wire:click="destroy" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
