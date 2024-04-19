<div class="form-group">
    <label style="font-size:1.1rem" class="mb-3" for="{{ $question->key }}">{{ $question->content }}</label>
    {{ $slot }}
    @if($errors->has($question->key))
        <div class="text-red-500 mt-3">{{ $errors->first($question->key) }}</div>
    @endif
</div>

{{--<div class="text-green-500">--}}
{{--    {{ $report ?? '' }}--}}
{{--</div>--}}
