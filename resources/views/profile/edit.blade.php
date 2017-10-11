@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="margin-top: 5%">
            <div class="col-md-8" style="margin: 0 auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $name }}'s Profile Settings</h4>
                        <p style="font-style:italic;">Note: Header photo, profile photo, and description are optional.</p>
                        <hr>
                        <form method="POST" action="{{ route('update', ['username' => $username]) }}">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="name" class="col-md-4 form-control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name" value="{{ old('name') ? old('name') : $name }}" placeholder="Enter your name." required autofocus>

                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="headerPhoto" class="col-md-4 form-control-label">URL to Header Photo</label>

                                <div class="col-md-6">
                                    <input id="headerPhoto" type="url" class="form-control{{ $errors->has('headerPhoto') ? ' is-invalid' : '' }}"
                                           name="headerPhoto" value="{{ old('headerPhoto') ? old('headerPhoto') : $headerPhoto }}" placeholder="Enter a valid URL to a photo.">

                                    @if ($errors->has('headerPhoto'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('headerPhoto') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="profilePhoto" class="col-md-4 form-control-label">URL to Profile Photo</label>

                                <div class="col-md-6">
                                    <input id="profilePhoto" type="url" class="form-control{{ $errors->has('profilePhoto') ? ' is-invalid' : '' }}"
                                           name="profilePhoto" value="{{ old('profilePhoto') ? old('profilePhoto') : $profilePhoto }}" placeholder="Enter a valid URL to a photo.">

                                    @if ($errors->has('profilePhoto'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('profilePhoto') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 form-control-label">Description</label>

                                <div class="col-md-6">
                                    <textarea id="description" name="description" rows="5" cols="30" id="description" type="description" maxlength="140"
                                              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                              style="resize:none;" placeholder="Enter a description for your profile.">{{ old('description') ? old('description') : $description }}</textarea>

                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 ml-md-auto">
                                    <a class="btn btn-danger" href="{{ sprintf('/%s', $username) }}">Cancel</a>
                                    <button type="submit" class="btn btn-success">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
