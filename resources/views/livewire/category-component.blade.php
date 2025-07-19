<div class="card">
    <div class="card-header">
        Manage category
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="search" class="form-control w-50 m-2" placeholder="Search category name">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Process</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a href="#" wire:click="update({{ $category->id }})" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal" data-bs-target="#updatePage">Update</a>
                                <a href="#" wire:click="confirm({{ $category->id }})" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deletePage">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $categories->links() }}
            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addPage">Add</a>
        </div>
    </div>

    {{-- Modal Add --}}
    <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Name</label>
                            @error('name') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="name">
                        </div>
                        <div class="form-group mb-2">
                            <label>Description</label>
                            @error('description') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <textarea class="form-control" wire:model="description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" wire:click="store">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update --}}
    <div wire:ignore.self class="modal fade" id="updatePage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Category</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Name</label>
                            @error('name') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="name">
                        </div>
                        <div class="form-group mb-2">
                            <label>Description</label>
                            @error('description') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <textarea class="form-control" wire:model="description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" wire:click="updateCategory">Update</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div wire:ignore.self class="modal fade" id="deletePage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                </div>
                <div class="modal-body">Are you sure you want to delete this category?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button class="btn btn-danger" wire:click="destroy">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>