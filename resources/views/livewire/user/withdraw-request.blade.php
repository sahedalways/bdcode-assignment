<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Withdraw Balance</h5>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row align-items-center">

                        <div class="col-md-6 mb-3">
                            <h5>Current Coins: <strong>{{ $coin_balance }}</strong></h5>
                            <h5>Balance: <strong>{{ $balance }}</strong></h5>
                        </div>

                        <div class="col-md-6">
                            <form>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Amount (Coins)<span class="text-danger">*</span></label>
                                    <input placeholder="Enter your withdraw coins" type="number" required
                                        class="form-control" wire:model="amount">
                                    @if ($errors->has('amount'))
                                        <span class="text-center text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary"
                                        wire:click="sendWithdrawRequest()">Withdraw</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
