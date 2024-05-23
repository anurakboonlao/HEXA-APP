@foreach($doctor_contacts->original['data'] as $key => $address)
<div class="invisible-checkboxes">
    <input type="radio" name="doctor_contact_ids" value="{{ $address['id'] }}" id="address{{ $key + 1 }}" required/>
    <label class="checkbox-alias" for="address{{ $key + 1 }}">
        <b class="font-weight-500">{{ $address['name'] }}</b><br>
        {{ $address['clinic'] }} <br>
        {{ $address['address'] }}
    </label>
</div>
@endforeach
<input type="hidden" name="name" value="">
<input type="hidden" name="clinic" value="">
<input type="hidden" name="address" value="">
<input type="hidden" name="phone" value="">