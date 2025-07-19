<div class="card">
    <div class="card-header">
        Manage Book
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="search" class="form-control w-50 m-2" placeholder="Search book title">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Writer</th>
                        <th>Publisher</th>
                        <th>ISBN</th>
                        <th>Published At</th>
                        <th>Quantity</th>
                        <th>Process</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->category->name ?? '-' }}</td>
                            <td>{{ $book->writer }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->published_at }}</td>
                            <td>{{ $book->quantity }}</td>
                            <td>
                                <a href="#" wire:click="update({{ $book->id }})" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal" data-bs-target="#updatePage">Update</a>
                                <a href="#" wire:click="confirm({{ $book->id }})" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deletePage">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $books->links() }}
            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addPage">Add</a>
        </div>
    </div>

    {{-- Modal Add --}}
    <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Book</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Title</label>
                            @error('title') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="text" class="form-control" wire:model="title">
                        </div>
                        <div class="form-group mb-2">
                            <label>Category</label>
                            @error('category_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Writer</label>
                            <input type="text" class="form-control" wire:model="writer">
                        </div>
                        <div class="form-group mb-2">
                            <label>Publisher</label>
                            <input type="text" class="form-control" wire:model="publisher">
                        </div>
                        <div class="form-group mb-2">
                            <label>ISBN</label>
                            <input type="text" class="form-control" wire:model="isbn">
                        </div>
                        <div class="form-group mb-2">
                            <label>Published At</label>
                            @error('published_at') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="date" class="form-control" wire:model="published_at">
                        </div>
                        <div class="form-group mb-2">
                            <label>Quantity</label>
                            @error('quantity') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="number" class="form-control" wire:model="quantity">
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
                    <h5 class="modal-title">Update Book</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Title</label>
                            <input type="text" class="form-control" wire:model="title">
                        </div>
                        <div class="form-group mb-2">
                            <label>Category</label>
                            <select class="form-control" wire:model="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Writer</label>
                            <input type="text" class="form-control" wire:model="writer">
                        </div>
                        <div class="form-group mb-2">
                            <label>Publisher</label>
                            <input type="text" class="form-control" wire:model="publisher">
                        </div>
                        <div class="form-group mb-2">
                            <label>ISBN</label>
                            <input type="text" class="form-control" wire:model="isbn">
                        </div>
                        <div class="form-group mb-2">
                            <label>Published At</label>
                            <input type="date" class="form-control" wire:model="published_at">
                        </div>
                        <div class="form-group mb-2">
                            <label>Quantity</label>
                            <input type="number" class="form-control" wire:model="quantity">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" wire:click="updateBook">Update</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div wire:ignore.self class="modal fade" id="deletePage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Book</h5>
                </div>
                <div class="modal-body">Are you sure you want to delete this book?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button class="btn btn-danger" wire:click="destroy">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>