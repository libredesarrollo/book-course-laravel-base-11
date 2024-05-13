@extends('dashboard.master')

@section('content')
     <h1>{{ $user->name }}</h1>

     <x-dashboard.user.role.permission.manage :user='$user'/>
@endsection