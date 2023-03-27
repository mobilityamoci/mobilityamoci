<div class="md:flex justify-center">


    <div class="m-10 md:w-3/4 space-y-8">
        @if($this->student)
            <livewire:single-student-edit></livewire:single-student-edit>
        @else

            <livewire:single-student-create></livewire:single-student-create>
        @endif
    </div>
</div>
