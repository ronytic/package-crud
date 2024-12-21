@extends('layouts.layout')

@section('title')
{{ __('CRUD Package') }}
@endsection

@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection

@section('breadcrumbs')
@include('shared.breadcrumbs', [
'routes' => [
    __('Admin') => route('admin.index'),
__('CRUD') => route('package.crud.index'),
],
'dynamic' => true,
])
@endsection

@section('css')
    <link rel="stylesheet" href="{{mix('/css/package.css', 'vendor/processmaker/packages/package-crud')}}">
@endsection
@section('content')
    <div class="container page-content" id="app-package-crud">
        <b-card>
            <div class="row" v-show="show" style="display: none">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-search"></i>
                        </span>
                        </div>
                        <input v-model="filter" class="form-control" placeholder="{{__('Search')}}...">
                    </div>
                </div>
                <div class="col-8">
                    <b-btn v-b-modal.sample-modal class="float-right btn-action"><i class="fa fa-plus"></i> {{__('Record')}}</b-btn>
                </div>
            </div>
            <div class="container-fluid">
                <crud-listing id="sample-list" ref="listing" :filter="filter" v-on:reload="reload"></crud-listing>
            </div>

            <b-modal id="sample-modal"
                    ref="modal"
                    ok-title="Save"
                    ok-variant="secondary"
                    @ok="onSubmit"
                    @hidden="clearForm"
                    cancel-title="Close"
                    cancel-variant="outline-secondary"
                    style="display: none;"
                    >
                <h5 slot="modal-header" class="modal-title">@{{ action }} Record</h5>
                <button slot="modal-header" type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                <div class="form-group">
                    {!!Form::label('name', __('Name'))!!}
                    {!!Form::text('name', null, ['class'=> 'form-control', 'v-model'=> 'crud.name', 'v-bind:class'
                    => '{\'form-control\':true, \'is-invalid\':addError.name}'])!!}
                    <div class="invalid-feedback" v-for="nameError in addError.name" v-text="nameError"></div>
                </div>
                <div class="form-group">
                    {!!Form::label('description', __('Description'))!!}
                    {!!Form::text('description', null, ['class'=> 'form-control', 'v-model'=> 'crud.description', 'v-bind:class'
                    => '{\'form-control\':true, \'is-invalid\':addError.description}'])!!}
                    <div class="invalid-feedback" v-for="nameError in addError.name" v-text="descriptionError"></div>
                </div>
                <div class="form-group">
                    {!!Form::label('code', __('Code'))!!}
                    {!!Form::text('code', null, ['class'=> 'form-control', 'v-model'=> 'crud.code', 'v-bind:class'
                    => '{\'form-control\':true, \'is-invalid\':addError.code}'])!!}
                    <div class="invalid-feedback" v-for="nameError in addError.name" v-text="codeError"></div>
                </div>
                <div class="form-group">
                    {!!Form::label('status', __('Status'))!!}
                    {!!Form::select('status', ["active" => "Active", "inactive" => "Inactive"], null, ['class'=> 'form-control', 'v-model'=> 'crud.status', 'v-bind:class'
                    => '{\'form-control\':true, \'is-invalid\':addError.status}'])!!}
                    <div class="invalid-feedback" v-for="statusError in addError.status" v-text="statusError"></div>
                </div>

            </b-modal>
        </b-card>
    </div>
@section('js')
<script src="{{mix('/js/package.js', 'vendor/processmaker/packages/package-crud')}}"></script>
@endsection
@endsection
