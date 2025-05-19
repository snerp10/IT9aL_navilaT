@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Categories Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Categories</li>
    </ol>
    
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Categories List -->
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-tags me-1"></i>
                    Product Categories
                </div>
                <div class="card-body">
                    @if(count($categories) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Products</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category->category_id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ Str::limit($category->description, 50) }}</td>
                                            <td>
                                                <a href="{{ route('products.index', ['category' => $category->category_id]) }}" class="badge bg-primary">
                                                    {{ $category->products_count ?? 0 }} products
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                        class="btn btn-sm btn-warning edit-category" 
                                                        data-id="{{ $category->category_id }}"
                                                        data-name="{{ $category->name }}"
                                                        data-description="{{ $category->description }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteCategoryModal" 
                                                        data-category-id="{{ $category->category_id }}" 
                                                        data-category-name="{{ $category->name }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            No categories found. Please add a category using the form.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Add/Edit Category Form -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">
                    <span id="formTitle">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add New Category
                    </span>
                </div>
                <div class="card-body">
                    <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div id="methodField"></div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-save"></i> Save Category
                            </button>
                            <button type="button" class="btn btn-secondary" id="resetFormBtn" style="display: none;">
                                <i class="bi bi-arrow-counterclockwise"></i> Cancel Edit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger">This will also remove the category from all associated products.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Edit Button Handler
        const editButtons = document.querySelectorAll('.edit-category');
        const categoryForm = document.getElementById('categoryForm');
        const formTitle = document.getElementById('formTitle');
        const methodField = document.getElementById('methodField');
        const submitBtn = document.getElementById('submitBtn');
        const resetFormBtn = document.getElementById('resetFormBtn');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        
        // Reset form to "Add" state
        function resetForm() {
            categoryForm.action = "{{ route('categories.store') }}";
            formTitle.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Add New Category';
            methodField.innerHTML = '';
            submitBtn.innerHTML = '<i class="bi bi-save"></i> Save Category';
            resetFormBtn.style.display = 'none';
            nameInput.value = '';
            descriptionInput.value = '';
        }
        
        // Set up edit functionality
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                
                // Update form for edit mode
                categoryForm.action = `/categories/${id}`;
                formTitle.innerHTML = '<i class="bi bi-pencil me-1"></i> Edit Category';
                methodField.innerHTML = '@method("PUT")';
                submitBtn.innerHTML = '<i class="bi bi-save"></i> Update Category';
                resetFormBtn.style.display = 'block';
                
                // Populate form fields
                nameInput.value = name;
                descriptionInput.value = description;
                
                // Scroll to the form
                categoryForm.scrollIntoView({ behavior: 'smooth' });
            });
        });
        
        // Reset button handler
        resetFormBtn.addEventListener('click', resetForm);
        
        // Delete modal handler
        const deleteModal = document.getElementById('deleteCategoryModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const categoryId = button.getAttribute('data-category-id');
                const categoryName = button.getAttribute('data-category-name');
                
                document.getElementById('deleteCategoryName').textContent = categoryName;
                document.getElementById('deleteCategoryForm').action = `/categories/${categoryId}`;
            });
        }
    });
</script>
@endsection
@endsection
