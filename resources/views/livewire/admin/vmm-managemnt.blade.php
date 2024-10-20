<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">VMM Management</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addVmm" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New VMM
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Search Here" wire:model="search">
                        </div>
                    </div>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        title
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs  opacity-7">
                                        Lifetime(Minutes)</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Minimum Invest</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Distribute Coins</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Execution Time(Seconds)</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Preparation Time(Minutes)</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Start Time</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($vmmData as $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->lifetime }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->minimum_invest }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->distribute_coin }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->execution_time }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->preparation_time }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->start_time }}</p>
                                        </td>

                                        <td>
                                            <select class="form-select text-sm font-weight-bold mb-0"
                                                wire:change="updateVmmStatus({{ $row->id }}, $event.target.value)">
                                                <option value="active" {{ $row->status == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="draft" {{ $row->status == 'draft' ? 'selected' : '' }}>
                                                    Draft</option>
                                                <option value="in_preparation"
                                                    {{ $row->status == 'in_preparation' ? 'selected' : '' }}>In
                                                    Preparation</option>
                                                <option value="running"
                                                    {{ $row->status == 'running' ? 'selected' : '' }}>Running</option>
                                                <option value="finished"
                                                    {{ $row->status == 'finished' ? 'selected' : '' }}>Finished
                                                </option>

                                            </select>
                                        </td>
                                        <td>
                                            <a data-bs-toggle="modal" data-bs-target="#editVmm"
                                                wire:click="edit({{ $row->id }})" type="button"
                                                class="badge badge-xs badge-warning fw-600 text-xs">
                                                Edit
                                            </a>

                                            <a href="#" wire:click="delete({{ $row->id }})" type="button"
                                                class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($hasMorePages)
                            <div x-data="{
                                init() {
                                    let observer = new IntersectionObserver((entries) => {
                                        entries.forEach(entry => {
                                            if (entry.isIntersecting) {
                                                @this.call('loadVmmData')
                                                console.log('loading...')
                                            }
                                        })
                                    }, {
                                        root: null
                                    });
                                    observer.observe(this.$el);
                                }
                            }"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                    Loading...
                                    <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade " id="addVmm" tabindex="-1" role="dialog" aria-labelledby="addVmm"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addVmm">Add VMM
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit="store">
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Title
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="Enter title"
                                    wire:model="title">

                                @error('title')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="col-md-12 mb-1">
                                <label class="form-label">Lifetime (Minutes) <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control" placeholder="Enter lifetime"
                                    wire:model="lifetime">


                                @error('lifetime')
                                    <span class="error text-danger">lifetime</span>
                                @enderror
                            </div>



                            <div class="col-md-12 mb-1">
                                <label class="form-label">Minimum Invest <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter minimum invest" wire:model="minimum_invest">
                                @error('minimum_invest')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Distribute Coin <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter distribute coin" wire:model="distribute_coin">
                                @error('distribute_coin')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Execution Time (Seconds)<span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter execution time" wire:model="execution_time">
                                @error('execution_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Preparation Time (Minutes)<span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter preparation time" wire:model="preparation_time">
                                @error('preparation_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" required class="form-control" wire:model="start_time"
                                    min="{{ now()->format('Y-m-d\TH:i') }}">
                                @error('start_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" wire:ignore.self id="editVmm" tabindex="-1" role="dialog" aria-labelledby="editVmm"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editVmm">
                        Edit VMM</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit="update">
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Title
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="Enter title"
                                    wire:model="title" readonly>

                                @error('title')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="col-md-12 mb-1">
                                <label class="form-label">Lifetime (Minutes) <span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control" placeholder="Enter lifetime"
                                    wire:model="lifetime">


                                @error('lifetime')
                                    <span class="error text-danger">lifetime</span>
                                @enderror
                            </div>



                            <div class="col-md-12 mb-1">
                                <label class="form-label">Minimum Invest <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter minimum invest" wire:model="minimum_invest">
                                @error('minimum_invest')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Distribute Coin <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter distribute coin" wire:model="distribute_coin">
                                @error('distribute_coin')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Execution Time (Seconds)<span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter execution time" wire:model="execution_time">
                                @error('execution_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Preparation Time (Minutes)<span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="Enter preparation time" wire:model="preparation_time">
                                @error('preparation_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" required class="form-control" wire:model="start_time"
                                    min="{{ now()->format('Y-m-d\TH:i') }}">
                                @error('start_time')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-1">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select text-sm font-weight-bold mb-0" required
                                    wire:model="status">
                                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="in_preparation"
                                        {{ $status == 'in_preparation' ? 'selected' : '' }}>In
                                        Preparation</option>
                                    <option value="running" {{ $status == 'running' ? 'selected' : '' }}>Running
                                    </option>
                                    <option value="finished" {{ $status == 'finished' ? 'selected' : '' }}>
                                        Finished
                                    </option>
                                </select>
                            </div>


                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
