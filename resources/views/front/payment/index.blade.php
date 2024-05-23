@extends('layout.front')
@section('content')
<div class="container body">
    <div class="main_container">
        @include('front.elements.clinic_sidebar')
        <div class="right_col" role="main" style="min-height: 1057px;">
			<div class="tab-content">
                <div class="tab-pane active">
                    <h3>Balance Due - Home</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection