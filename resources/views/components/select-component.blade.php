<div class="form-group">
    <label for="{{$id}}">{{ $title }} <span class="text-danger">{{ $isRequired == 'true'? '*' : '' }}</span></label>
    <select class="x-input-component form-control form-control-border" id="{{$id}}">
        @foreach($options as $option => $displayText)
            <option value="{{$option}}" @if($value) {{ $value == $option? 'selected' : '' }} @endif>{{$displayText}}</option>
        @endforeach
    </select>
</div>
