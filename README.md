# Model2TS

## What is it?

An artisan command that translates Eloquent models into Typescript interfaces.

Model2TS checks the model structure using reflection techniques, and the related table structure in order to properly infer the correct types.

## Installation

        composer require juanparati/model2ts --dev
        
## Usage

Generate Typescript interface for a specific model:

        artisan model2ts:generate "App\Models\User" user.ts
        
The following parameters can change the behaviour of the command:

        --ignore-hidden     : Discard attributes registered in $hidden property
        --ignore-casts      : Do not use $casts property in order to infer the attributes types
        --ignore-appends    : Do not include virtual accessor located in $appends property
        --ignore-accessors  : Do not infer types from model accessors.


## Accessor's type inference

Model2Ts can infer from the native accessors and also from the virtual ones registered in the $appends property.

In order to make it work the type inference is require to define a return type in each of the accessor methods.

Example:

        public function getFirstnameAttribute() : string {
            return explode(' ', $this->attributes['name'])[0];
        }


## Data structure inference

Some database types is difficult to infer the type of some attributes, because it may be used in order to store structure data. This is the case of blob and text fields. In this case the interface file will contain a comment indicating that field may be content a structure type like an object or an array.