@extends('layouts.admin')

@section('title', strtoupper($status.' '.$code))

@section('content')
<div class="error-page">
        <h2 class="headline text-yellow"> {{ $code }}</h2>

        <div class="error-content">
          <h2><i class="fa fa-warning text-yellow"></i> {{ $message }}.</h2>
        </div>
        <!-- /.error-content -->
      </div>
@endsection