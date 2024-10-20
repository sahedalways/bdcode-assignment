<?php

namespace App\Livewire\Admin;

use App\Models\VMM;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class VmmManagemnt extends Component
{
    public $vmmData, $singleVmmData, $title, $lifetime, $minimum_invest, $distribute_coin, $execution_time, $preparation_time, $start_time, $status = 'active', $search;

    public $editMode = false;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    protected $rules = [
        'title' => 'required|string|max:255|unique:v_m_m_s,title',
        'lifetime' => 'required|integer|min:1',
        'minimum_invest' => 'required|integer|min:1',
        'distribute_coin' => 'required|integer|min:1',
        'execution_time' => 'required|integer|min:1',
        'preparation_time' => 'required|integer|min:1',
        'start_time' => 'required|date',
    ];



    public function mount()
    {
        $this->vmmData = new EloquentCollection();

        $this->loadVmmData();
    }


    public function render()
    {
        return view('livewire.admin.vmm-managemnt');
    }


    public function resetInputFields()
    {
        $this->singleVmmData = '';
        $this->title = '';
        $this->lifetime = null;
        $this->minimum_invest = null;
        $this->distribute_coin = null;
        $this->execution_time = null;
        $this->preparation_time = null;
        $this->start_time = null;
        $this->status = 'active';
        $this->resetErrorBag();

    }


    public function refresh()
    {
        /* if search query or order filter is empty */
        if ($this->search == '') {
            $this->vmmData = $this->vmmData->fresh();
        }
    }



    /* store customer data */
    public function store()
    {
        $this->validate();


        $vmm = new VMM();

        $vmm->title = $this->title;
        $vmm->lifetime = $this->lifetime;
        $vmm->minimum_invest = $this->minimum_invest;
        $vmm->distribute_coin = $this->distribute_coin;
        $vmm->execution_time = $this->execution_time;
        $vmm->preparation_time = $this->preparation_time;
        $vmm->start_time = $this->start_time;
        $vmm->status = $this->status;
        $vmm->save();

        $this->vmmData = VMM::latest()->get();
        $this->resetInputFields();

        $this->dispatch('toast', message: 'VMM has been created.', notify: 'success');
        $this->dispatch('closemodal');

    }



    /* view vmm details to update */
    public function edit($id)
    {
        $this->editMode = true;
        $this->singleVmmData = VMM::where('id', $id)->first();
        $this->title = $this->singleVmmData->title;
        $this->lifetime = $this->singleVmmData->lifetime;
        $this->minimum_invest = $this->singleVmmData->minimum_invest;
        $this->distribute_coin = $this->singleVmmData->distribute_coin;
        $this->execution_time = $this->singleVmmData->execution_time;
        $this->preparation_time = $this->singleVmmData->preparation_time;
        $this->start_time = $this->singleVmmData->start_time;
        $this->status = $this->singleVmmData->status;
    }
    /* update customer details */
    public function update()
    {
        $this->validate([
            'lifetime' => 'required|integer|min:1',
            'minimum_invest' => 'required|integer|min:1',
            'distribute_coin' => 'required|integer|min:1',
            'execution_time' => 'required|integer|min:1',
            'preparation_time' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'status' => 'required|in:draft,active,in_preparation,running,finished',
        ]);


        $this->singleVmmData->title = $this->title;
        $this->singleVmmData->lifetime = $this->lifetime;
        $this->singleVmmData->minimum_invest = $this->minimum_invest;
        $this->singleVmmData->distribute_coin = $this->distribute_coin;
        $this->singleVmmData->execution_time = $this->execution_time;
        $this->singleVmmData->preparation_time = $this->preparation_time;
        $this->singleVmmData->start_time = $this->start_time;
        $this->singleVmmData->status = $this->status;
        $this->singleVmmData->save();
        $this->refresh();
        $this->resetInputFields();
        $this->editMode = false;

        $this->dispatch('toast', message: 'VMM has been updated.', notify: 'success');
        $this->dispatch('closemodal');

    }



    public function updated($name, $value)
    {
        if ($name == 'search' && $value != '') {
            $this->vmmData = VMM::where('title', 'like', '%' . $value)->latest()->get();
            $this->reloadVmmData();
        } elseif ($name == 'search' && $value == '') {

            $this->vmmData = new EloquentCollection();
            $this->reloadVmmData();
        }
        /*if the updated element is address */
        if ($name == 'address' && $value != '') {
            $this->address = $value;
        }
    }


    public function loadVmmData()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $vmmDataList = $this->filterData();
        $this->vmmData->push(...$vmmDataList->items());
        if ($this->hasMorePages = $vmmDataList->hasMorePages()) {
            $this->nextCursor = $vmmDataList->nextCursor()->encode();
        }
        $this->currentCursor = $vmmDataList->cursor();
    }



    public function reloadVmmData()
    {
        $this->vmmData = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $vmmInfos = $this->filterData();
        $this->vmmData->push(...$vmmInfos->items());
        if ($this->hasMorePages = $vmmInfos->hasMorePages()) {
            $this->nextCursor = $vmmInfos->nextCursor()->encode();
        }
        $this->currentCursor = $vmmInfos->cursor();
    }


    public function filterData()
    {
        if ($this->search || $this->search != '') {
            $vmmInfos = VMM::where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $vmmInfos;

        } else {

            $vmmInfos = VMM::latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $vmmInfos;

        }
    }


    public function updateVmmStatus($id, $newStatus)
    {
        $vmm = VMM::find($id);

        if ($vmm) {
            $vmm->status = $newStatus;
            $vmm->save();

            $this->dispatch('toast', message: 'VMM status updated successfully!', notify: 'success');
        } else {
            $this->dispatch('toast', message: 'VMM not found!', notify: 'error');
        }
    }


    public function delete($id)
    {
        try {
            VMM::where('id', $id)->delete();

            $this->vmmData = VMM::latest()->get();

            $this->dispatch('toast', message: 'VMM deleted successfully!', notify: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Cannot Delete the VMM!', notify: 'error');
        }
    }


}