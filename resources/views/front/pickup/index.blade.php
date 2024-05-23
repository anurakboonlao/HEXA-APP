{{ Form::open(['files' => true, 'url' => 'front/pickup/'.$member->id, 'class' => 'font-pick1', 'id' => 'formfield', 'method' => 'POST'])}}
    <label>คลินิก/โรงพยาบาล</label>
    {{ Form::textarea('location', $member->name, ['class' => 'form-add1 form-pick1', 'id' => 'location', 'required']) }}
    <div class="row">
        <div class="col-lg-6">
            <label>เลือกช่วงเวลา</label>
            {{ Form::select('time', ['' => 'เลือกช่วงเวลา'] + pickup_time(), '', ['class' => 'wrap-drop', 'id' => 'pickup_time', 'required']) }}
        </div>
        <div class="col-lg-6">
            <label>จำนวนงาน</label>
            {{ Form::number('total_case', '', ['class' => 'form-add1', 'id' => 'total_case', 'min' => '0', 'required'])}}
        </div>
    </div>
    <label>ทันตแพทย์</label>
    {{ Form::text('doctor_name', '', ['class' => 'form-add1', 'id' => 'doctor_name', 'required'])}}
    <label>คนไข้</label>
    {{ Form::text('patient_name', '', ['class' => 'form-add1', 'id' => 'patient_name', 'required'])}}

    <button class="btn-rewa4 btn-pick1 float-right" type="button" id="submitBtn" data-toggle="modal" data-target="#modal-get-job1">เรียกรับงาน</button>

{{Form::close()}}

