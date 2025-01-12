@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Add New Amdin</div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if (session('failure'))
                    <div class="alert alert-danger">
                        {{ session('failure') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('addNewAdmin') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="masterPassword" class="form-label">Master Password</label>
                            <input type="password" name="masterPassword" id="masterPassword" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" name="fullName" id="fullName" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="cpassword" class="form-label">Confirm Password</label>
                            <input type="password" name="cpassword" id="cpassword" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="dateOfBirth" class="form-label">Date of birth</label>
                            <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" required>
                        </div>


                        <div class="mb-3">
                            <label for="authorization" class="form-label">Authorization</label>
                            <select class="form-select" id="authorization" name="authorization">
                                <option value="active" selected>Active</option>
                                <option value="revoked">Revoked</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="scholar" selected>Scholar</option>
                                <option value="master">Master</option>
                            </select>
                        </div>


                        <button type="submit" class="btn btn-primary w-100">Add New Amdin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
