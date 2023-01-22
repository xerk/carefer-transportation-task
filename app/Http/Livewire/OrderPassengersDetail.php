<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Seat;
use App\Models\Order;
use Livewire\Component;
use App\Models\Passenger;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderPassengersDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Order $order;
    public Passenger $passenger;
    public $usersForSelect = [];
    public $seatsForSelect = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Passenger';

    protected $rules = [
        'passenger.type' => ['required', 'in:guest,user'],
        'passenger.user_id' => ['nullable', 'exists:users,id'],
        'passenger.seat_id' => ['required', 'exists:seats,id'],
    ];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->usersForSelect = User::pluck('name', 'id');
        $this->seatsForSelect = Seat::pluck('referance', 'id');
        $this->resetPassengerData();
    }

    public function resetPassengerData()
    {
        $this->passenger = new Passenger();

        $this->passenger->type = 'guest';
        $this->passenger->user_id = null;
        $this->passenger->seat_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newPassenger()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.order_passengers.new_title');
        $this->resetPassengerData();

        $this->showModal();
    }

    public function editPassenger(Passenger $passenger)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.order_passengers.edit_title');
        $this->passenger = $passenger;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal()
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal()
    {
        $this->showingModal = false;
    }

    public function save()
    {
        $this->validate();

        if (!$this->passenger->order_id) {
            $this->authorize('create', Passenger::class);

            $this->passenger->order_id = $this->order->id;
        } else {
            $this->authorize('update', $this->passenger);
        }

        $this->passenger->save();

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', Passenger::class);

        Passenger::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetPassengerData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->order->passengers as $passenger) {
            array_push($this->selected, $passenger->id);
        }
    }

    public function render()
    {
        return view('livewire.order-passengers-detail', [
            'passengers' => $this->order->passengers()->paginate(20),
        ]);
    }
}
