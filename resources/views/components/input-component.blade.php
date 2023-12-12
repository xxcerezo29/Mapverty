<div class="form-group" {{ $isHidden === true? 'hidden' : '' }}>
    <label for="{{$id}}">{{ $title }} <span class="text-danger">{{ $customValidation?? '' }}</span> <span class="text-danger">{{ $isRequired == 'true'? '*' : '' }}</span></label>
    <input type="{{ $type }}" value="{{$value}}" class="x-input-component form-control form-control-border {{$isError? 'is-invalid' : ''}}" id="{{$id}}" placeholder="{{$placeholder}}">
</div>
