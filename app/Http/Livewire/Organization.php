<?php

namespace App\Http\Livewire;

use App\Models\Organization as ModelsOrganization;
use Livewire\Component;
use Livewire\WithFileUploads;

class Organization extends Component
{
    use WithFileUploads;
    public $openForm;
    public $isEdit;
    public $organization;
    public $organizations;
    public $keyword;

    public function mount()
    {
        $this->resetComponent();
        $this->organizations = ModelsOrganization::all();
    }

    public function addRecord()
    {
        $this->resetComponent();
        $this->openForm = true;
    }

    public function editRecord(ModelsOrganization $organization)
    {
        $this->isEdit = true;
        $this->organization = $organization;
        $this->openForm = true;
    }

    private function resetComponent()
    {
        $this->organizations = ModelsOrganization::all();
        $this->fill([
            'keyword' => null,
            'isEdit' => false,
            'openForm' => false,
            'organization' => [
                'name' => null,
                'email' => null,
                'phone' => null,
                'website' => null,
                'logo' => null,
                'logo_url' => null,
            ]
        ]);
    }

    public function submit()
    {
        $this->isEdit ? $this->update() : $this->create();
    }

    public function search()
    {
        $this->organizations = ModelsOrganization::where('name', 'like', "%$this->keyword%")->orWhereHas('persons', function($person) {
            $person->where('name', 'like', "%pic%");
        })->get();
    }

    private function create()
    {
        $organization = ModelsOrganization::create($this->organization);
        if (isset($this->organization['logo'])) {
            $organization->updateLogo($this->organization['logo']);
        }
        $this->emit('saved');
        $this->resetComponent();
    }

    private function update()
    {
        $organization = ModelsOrganization::create($this->organization);

        $organization->updateLogo($this->organization['logo']);
        $this->emit('saved');
        $this->resetComponent();
    }

    public function render()
    {
        return view('livewire.organization');
    }
}
