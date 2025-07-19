<div class="card">
    <div class="card-header">
        Manage Borrowers
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="search" class="form-control w-50 m-2" placeholder="search by user name"
            name="" id="">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Book Title</th>
                        <th scope="col">Borrower Name</th>
                        <th scope="col">Loan Date</th>
                        <th scope="col">Return Date</th>
                        <th scope="col">Status</th>
                        <th>Process</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowers as $borrower)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $borrower->book->title ?? 'N/A' }}</td>
                            <td>{{ $borrower->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrower->loan_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrower->return_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $borrower->status == 'returned' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($borrower->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="" wire:click="update({{ $borrower->id }})" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal" data-bs-target="#updatePage">Update</a>
                                <a href="" wire:click="confirm({{ $borrower->id }})" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deletePage">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $borrowers->links() }}

            <a href="" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addPage">Add</a>
        </div>
    </div>

    <!-- Modal Add -->
    <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label for="">Book</label>
                            @error('book_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="book_id">
                                <option value="">Select Book</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">User</label>
                            @error('user_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Loan Date</label>
                            @error('loan_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="date" class="form-control" wire:model="loan_date">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Return Date</label>
                            @error('return_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="date" class="form-control" wire:model="return_date">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Status</label>
                            @error('status')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="status">
                                <option value="">Select Status</option>
                                <option value="borrow">Borrow</option>
                                <option value="returned">Returned</option>
                            </select>
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

    <!-- Modal update -->
    <div wire:ignore.self class="modal fade" id="updatePage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label for="">Book</label>
                            @error('book_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="book_id">
                                <option value="">Select Book</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">User</label>
                            @error('user_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Loan Date</label>
                            @error('loan_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="date" class="form-control" wire:model="loan_date">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Return Date</label>
                            @error('return_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="date" class="form-control" wire:model="return_date">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Status</label>
                            @error('status')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select class="form-control" wire:model="status">
                                <option value="">Select Status</option>
                                <option value="borrow">Borrow</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="updateBorrower" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div wire:ignore.self class="modal fade" id="deletePage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sure to delete this borrower record?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" wire:click="destroy" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>