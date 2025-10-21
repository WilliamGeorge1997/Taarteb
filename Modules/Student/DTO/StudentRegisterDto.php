<?php

namespace Modules\Student\DTO;

class StudentRegisterDto
{
    public $name;
    public $email;
    public $identity_number;
    public $parent_email;
    public $parent_phone;
    public $gender;
    public $grade_id;
    public $school_id;
    public $is_fee_paid;
    public $is_register;
    public $address;
    public $region_id;
    public $branch_id;
    public $name_en;
    public $birth_date;
    public $education_level;
    public $has_learning_difficulties;
    public $educational_system;
    public $behavioral_data;
    public $pronunciation;
    public $chronic_diseases;
    public $food_allergies;
    public $other_notes;
    public $transport;
    public $street_number;
    public $house_number;
    public $nearest_landmark;
    public $home_location_url;
    public $siblings_count;

    public function __construct($request)
    {
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('email'))
            $this->email = $request->get('email');
        if ($request->get('gender'))
            $this->gender = $request->get('gender');
        if ($request->get('identity_number'))
            $this->identity_number = $request->get('identity_number');
        if ($request->get('parent_email'))
            $this->parent_email = $request->get('parent_email');
        if ($request->get('parent_phone'))
            $this->parent_phone = $request->get('parent_phone');
        if ($request->get('grade_id'))
            $this->grade_id = $request->get('grade_id');
        if ($request->get('school_id'))
            $this->school_id = $request->get('school_id');
        if ($request->get('address'))
            $this->address = $request->get('address');
        $this->is_register = 1;
        $this->is_fee_paid = 0;
        if ($request->get('region_id'))
            $this->region_id = $request->get('region_id');
        if ($request->get('branch_id'))
            $this->branch_id = $request->get('branch_id');
        if ($request->get('name_en'))
            $this->name_en = $request->get('name_en');
        if ($request->get('birth_date'))
            $this->birth_date = $request->get('birth_date');
        if ($request->get('education_level'))
            $this->education_level = $request->get('education_level');
        if ($request->get('has_learning_difficulties'))
            $this->has_learning_difficulties = $request->get('has_learning_difficulties');
        if ($request->get('educational_system'))
            $this->educational_system = $request->get('educational_system');
        if ($request->get('behavioral_data'))
            $this->behavioral_data = $request->get('behavioral_data');
        if ($request->get('pronunciation'))
            $this->pronunciation = $request->get('pronunciation');
        if ($request->get('chronic_diseases'))
            $this->chronic_diseases = $request->get('chronic_diseases');
        if ($request->get('food_allergies'))
            $this->food_allergies = $request->get('food_allergies');
        if ($request->get('other_notes'))
            $this->other_notes = $request->get('other_notes');
        if ($request->get('transport'))
            $this->transport = $request->get('transport');
        if ($request->get('street_number'))
            $this->street_number = $request->get('street_number');
        if ($request->get('house_number'))
            $this->house_number = $request->get('house_number');
        if ($request->get('nearest_landmark'))
            $this->nearest_landmark = $request->get('nearest_landmark');
        if ($request->get('home_location_url'))
            $this->home_location_url = $request->get('home_location_url');
        if ($request->get('siblings_count'))
            $this->siblings_count = $request->get('siblings_count');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->email == null)
            unset($data['email']);
        if ($this->identity_number == null)
            unset($data['identity_number']);
        if ($this->parent_email == null)
            unset($data['parent_email']);
        if ($this->parent_phone == null)
            unset($data['parent_phone']);
        if ($this->gender == null)
            unset($data['gender']);
        if ($this->grade_id == null)
            unset($data['grade_id']);
        if ($this->school_id == null)
            unset($data['school_id']);
        if ($this->address == null)
            unset($data['address']);
        if ($this->region_id == null)
            unset($data['region_id']);
        if ($this->branch_id == null)
            unset($data['branch_id']);
        if ($this->name_en == null)
            unset($data['name_en']);
        if ($this->birth_date == null)
            unset($data['birth_date']);
        if ($this->education_level == null)
            unset($data['education_level']);
        if ($this->has_learning_difficulties == null)
            unset($data['has_learning_difficulties']);
        if ($this->educational_system == null)
            unset($data['educational_system']);
        if ($this->behavioral_data == null)
            unset($data['behavioral_data']);
        if ($this->pronunciation == null)
            unset($data['pronunciation']);
        if ($this->chronic_diseases == null)
            unset($data['chronic_diseases']);
        if ($this->food_allergies == null)
            unset($data['food_allergies']);
        if ($this->other_notes == null)
            unset($data['other_notes']);
        if ($this->transport == null)
            unset($data['transport']);
        if ($this->street_number == null)
            unset($data['street_number']);
        if ($this->house_number == null)
            unset($data['house_number']);
        if ($this->nearest_landmark == null)
            unset($data['nearest_landmark']);
        if ($this->home_location_url == null)
            unset($data['home_location_url']);
        if ($this->siblings_count == null)
            unset($data['siblings_count']);
        return $data;
    }
}
