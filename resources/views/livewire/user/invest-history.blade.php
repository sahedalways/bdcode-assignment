<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Invest History</h5>
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
                                        Distribute Coins</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Execution Time(Seconds)</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Preparation Time(Minutes)</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Start Time</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        My Investment</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($investData as $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->lifetime }}</p>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->distribute_coin }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->execution_time }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->preparation_time }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->vmm->start_time }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->amount }}</p>
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
                                                @this.call('loadInvests')
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

</div>
