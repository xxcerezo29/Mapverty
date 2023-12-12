@switch($question->type)
    @case('1')
        <div class="form-group">
            <label for="question-{{$question->id}}">{{ $question->question }} <span class="text-danger">*</span></label>
            <select class="x-input-component form-control form-control-border" id="question-{{$question->id}}-input">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        @break
    @case('2')
        @if(count($choices) > 0)
            <div class="form-group">
                <label for="question-{{$question->id}}">{{ $question->question }} <span class="text-danger">*</span></label>
                <select class="x-input-component form-control form-control-border" id="question-{{$question->id}}-input">
                    @foreach($choices as $choice)
                        <option value="{{$choice->id}}">{{$choice->choice}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="form-group">
                <label for="question-{{$question->id}}">{{ $question->question }} <span class="text-danger">(No Choices Available)</span></label>
            </div>
        @endif
        @break
    @case('3')
        <div class="form-group">
            <label for="question-{{$question->id}}">{{ $question->question }} <span class="text-danger"> {{ $customValidation?? '' }}</span> <span class="text-danger">*</span></label>
            <input type="text" class="x-input-component form-control form-control-border" id="question-{{$question->id}}-input">
        </div>
        @break
    @case('4')
        <div class="form-group">
            <label for="question-{{$question->id}}">{{ $question->question }} <span class="text-danger"> {{ $customValidation?? '' }}</span> <span class="text-danger">*</span></label>
            <input type="Number" class="x-input-component form-control form-control-border " id="question-{{$question->id}}-input">
        </div>
        @break
@endswitch
