<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">VMM List</h5>
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
                                @foreach ($vmmList as $row)
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
                                            <p class="text-sm font-weight-bold mb-0">
                                                @if ($row->status === 'draft')
                                                    Draft
                                                @elseif($row->status === 'active')
                                                    Active
                                                @elseif($row->status === 'in_preparation')
                                                    In Preparation
                                                @elseif($row->status === 'running')
                                                    Running
                                                @elseif($row->status === 'finished')
                                                    Finished
                                                @else
                                                    {{ $row->status }}
                                                @endif
                                            </p>
                                        </td>

                                        @if ($row->status === 'active')
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#vmmDetails"
                                                    wire:click="details({{ $row->id }})" type="button"
                                                    class="badge badge-xs badge-warning fw-600 text-xs">
                                                    Invest
                                                </a>
                                            </td>
                                        @endif

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
                                                @this.call('loadVmm')
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
    <div wire:ignore.self class="modal fade " id="vmmDetails" tabindex="-1" role="dialog" aria-labelledby="vmmDetails"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="vmmDetails">Active VMM
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h4 class="mb-3">{{ $title }}</h4>
                        <p><strong>Winning Coins:</strong> {{ $distribute_coin }}</p>
                    </div>

                    <ul>
                        <li class="mb-2">Start at: {{ $start_time }}</li>
                        <li class="mb-2">Execution/Running time: {{ $lifetime }} seconds</li>
                        <li class="mb-2">Minimum investment: ${{ $minimum_invest }}</li>
                        <li class="mb-2">Preparation time: {{ $preparation_time }} minutes</li>
                        <li>My investment: ${{ $user_invest }}</li>
                    </ul>
                    <div class="text-center">
                        <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#investModal">Invest
                            Now</button>
                    </div>

                </div>

            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade " id="investModal" tabindex="-1" role="dialog"
        aria-labelledby="investModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="investModalLabel">Investment Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="investmentForm">
                        <div class="mb-3">
                            <label for="invest_amount" class="form-label">Enter Amount</label>
                            <input placeholder="Enter your invest amount" type="number" required class="form-control"
                                wire:model="invest_amount">
                        </div>
                        @if ($errors->has('invest_amount'))
                            <span class="text-center text-danger">{{ $errors->first('invest_amount') }}</span>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="submitInvestment()">Submit
                        Investment</button>
                </div>
            </div>
        </div>
    </div>
</div>
