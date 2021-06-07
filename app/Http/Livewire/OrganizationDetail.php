<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationDetail extends Component
{
    use WithFileUploads;
    public $organization;
    public $openForm;
    public $person;
    public $persons;
    public $isEdit;
    public $pics;
    public $createNew;

    public function addPerson()
    {
        $this->fetchPics();
        $this->resetComponent();
        $this->openForm = true;
    }

    public function mount(Organization $organization)
    {
        $this->organization = $organization;
        $this->fetchPersons();
        $this->fetchPics();
    }

    private function resetComponent()
    {
        $this->fill([
            'createNew' => 'new',
            'isEdit' => false,
            'openForm' => false,
            'person' => [
                'name' => null,
                'email' => null,
                'phone' => null,
                'photo' => null,
                'profile_photo_url' => null,
            ]
        ]);
    }

    public function submit()
    {
        if($this->createNew === 'new') {
            $this->isEdit ? $this->update() : $this->create();
        }

        $person = User::find($this->createNew);
        $person->organization()->associate($this->organization)->save();
        $this->fetchPersons();
        $this->resetComponent();
    }

    private function update()
    {

    }

    private function create()
    {
        $this->person = array_merge($this->person, [
            'role' => User::ADMIN,
            'organization_id' => $this->organization->id,
            'password' => Hash::make("default")
        ]);

        $person = User::create($this->person);
        if($person) {
            if(isset($this->person['photo'])){
                $person->updateProfilePhoto($this->person['photo']);
            }
            // $person->organization()->associate($this->organization);
            $this->emit('saved');
            $this->resetComponent();
            $this->fetchPersons();
        }
        
    }

    public function setAs(User $person, $role)
    {
        $role = $role === 'pic' ? User::PIC : User::ACCOUNT_MANAGER;
        $person->update([
            'role' => $role
        ]);
        $this->fetchPersons();
    }

    public function removePic(User $person)
    {
        $this->setAs($person, 'pic');
        $person->organization()->dissociate();
        $person->save();
        $this->fetchPersons();
    }

    private function fetchPics()
    {
        $this->pics = User::whereNull('organization_id')->where('role', '<>', User::ADMIN)->get();
    }

    private function fetchPersons()
    {
        $this->persons = User::where(['organization_id' => $this->organization->id])->get();
    }

    public function render()
    {
        return view('livewire.organization-detail');
    }
}
