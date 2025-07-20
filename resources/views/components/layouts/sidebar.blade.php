<div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
    <h4 class="text-white mb-4"><i class="bi bi-book"></i> Library</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a href="{{ route('home') }}" class="nav-link text-white">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('member') }}" class="nav-link text-white">
                <i class="bi bi-people"></i> Manage Members
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('book') }}" class="nav-link text-white">
                <i class="bi bi-journal-bookmark"></i> Manage Books
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('borrower') }}" class="nav-link text-white">
                <i class="bi bi-pencil-square"></i> Manage Loans
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('category') }}" class="nav-link text-white">
                <i class="bi bi-tags"></i> Manage Categories
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user') }}" class="nav-link text-white">
                <i class="bi bi-person-badge"></i> Manage Staff
            </a>
        </li>
    </ul>
</div>