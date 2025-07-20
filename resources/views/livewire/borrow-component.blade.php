<div class="card">
    <div class="card-header">
        Manage Borrowers
    </div>
    <div class="card-body">
        <input type="text" wire:model.live="search" class="form-control w-50 m-2" placeholder="Search by user name">

        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Book Title</th>
                        <th>Borrower Name</th>
                        <th>Loan Date</th>
                        <th>Return Date</th>
                        <th>Actual Return</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Fine days</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowers as $borrower)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $borrower->book->title ?? 'N/A' }}</td>
                            <td>{{ $borrower->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrower->loan_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrower->return_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($borrower->actual_return_date)
                                    {{ \Carbon\Carbon::parse($borrower->actual_return_date)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($borrower->status == 'returned')
                                    <span class="badge bg-success">Returned</span>
                                @elseif($borrower->isOverdue())
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-warning">Borrowed</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $fineAmount = abs($borrower->fine_amount);
                                @endphp
                                @if($fineAmount > 0)
                                    <span class="badge bg-danger">
                                        Rp {{ number_format($fineAmount, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Rp 0</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $fineDays = abs($borrower->fine_days);
                                @endphp
                                @if($fineDays > 0)
                                    <span class="badge bg-danger">{{ $fineDays }} days</span>
                                @else
                                    <span class="badge bg-success">0 days</span>
                                @endif
                            </td>
                            <td>
                                <button wire:click="update({{ $borrower->id }})" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal" data-bs-target="#updatePage">Update</button>
                                <button wire:click="confirm({{ $borrower->id }})" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#deletePage">Delete</button>
                                @if($borrower->status === 'borrow')
                                    <button wire:click="returnBook({{ $borrower->id }})"
                                        class="btn btn-sm btn-success">Return</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $borrowers->links() }}
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addPage">Add</button>
        </div>
    </div>

    <!-- Modal Add -->
    <div wire:ignore.self class="modal fade" id="addPage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Book</label>
                            @error('book_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="book_id">
                                <option value="">Select Book</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }} (Available:
                                        {{ $book->quantity - $book->total_borrowed }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>User</label>
                            @error('user_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Loan Date</label>
                            @error('loan_date') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="date" class="form-control" wire:model="loan_date">
                        </div>
                        <div class="form-group mb-2">
                            <label>Return Date</label>
                            @error('return_date') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="date" class="form-control" wire:model="return_date">
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
    <div wire:ignore.self class="modal fade" id="updatePage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Update Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-2">
                            <label>Book</label>
                            @error('book_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="book_id">
                                <option value="">Select Book</option>
                                @foreach($allBooks as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>User</label>
                            @error('user_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Loan Date</label>
                            @error('loan_date') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="date" class="form-control" wire:model="loan_date">
                        </div>
                        <div class="form-group mb-2">
                            <label>Return Date</label>
                            @error('return_date') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <input type="date" class="form-control" wire:model="return_date">
                        </div>
                        <div class="form-group mb-2">
                            <label>Status</label>
                            @error('status') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <select class="form-control" wire:model="status">
                                <option value="">Select Status</option>
                                <option value="borrow">Borrow</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        @if($status === 'returned')
                            <div class="form-group mb-2">
                                <label>Actual Return Date</label>
                                <input type="date" class="form-control" wire:model="actual_return_date">
                            </div>
                        @endif
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
                    <h1 class="modal-title fs-5">Delete Borrower</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sure to delete this borrower record?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" wire:click="destroy" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>