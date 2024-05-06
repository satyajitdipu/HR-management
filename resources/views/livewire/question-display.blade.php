<div class="container-fluid">
    <div class="row pt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Question {{$this->slno}}: {{$this->question->questions}}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <legend>Answer Choices</legend>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" id="choice1" value="A" wire:model="selectedOption">
                                <label class="form-check-label" for="choice1">{{ $this->question->option_a }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" id="choice2" value="B" wire:model="selectedOption">
                                <label class="form-check-label" for="choice2">{{ $this->question->option_b }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" id="choice3" value="C" wire:model="selectedOption">
                                <label class="form-check-label" for="choice3">{{ $this->question->option_c }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" id="choice4" value="D" wire:model="selectedOption">
                                <label class="form-check-label" for="choice4">{{ $this->question->option_d }}</label>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Status
                </div>
                <div class="card-body">
                    <p>Answered</p>
                    <div class="col-md-12">
                    <div class="col-md-12">
                    @for ($i = 1; $i <= $this->totalQuestions; $i++)
    @php
        $colorClass = '';
        $isReview = App\Models\QuestionAttempt::where('user_id', Auth::user()->id)
            ->where('question_id', $i)
            ->where('is_review',1)
            ->exists();
            $is = App\Models\QuestionAttempt::where('user_id', Auth::user()->id)
            ->where('question_id', $i)
            ->where('no_answer',0)
            ->exists();
        if ($i == $this->slno) {
            $colorClass = 'active'; // Set the active color
        } else {
            // Set other colors as needed
            // Add more elseif conditions if you have additional cases
        }
    @endphp
    <a href="#" wire:click="loadQuestion({{ $i }})">
        <div class="rectangular-element {{ $colorClass }} {{ ($colorClass != 'active' && $isReview) ? 'isReview' : '' }}">
            <span class="question-number">{{ $i }}</span>
        </div>
    </a>
@endfor

</div>


        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        
  
    <div class="col-md-12">
    <button type="button" class="btn btn-primary me-2 text-dark" wire:click="saveAndNext">SAVE & NEXT</button>
    <button type="button" class="btn btn-secondary me-2 text-dark" wire:click="saveAndMarkForReview">SAVE & MARK FOR REVIEW</button>
    <button type="button" class="btn btn-outline-secondary me-2 text-dark" wire:click="clearResponse">CLEAR RESPONSE</button>
    <button type="button" class="btn btn-secondary me-2 text-dark" wire:click="markForReviewAndNext">MARK FOR REVIEW & NEXT</button>
    <button type="button" class="btn btn-outline-primary me-2 text-dark" wire:click="goBack">BACK</button>
    <button type="button" class="btn btn-primary text-white text-dark" wire:click="goNext">NEXT >> </button>
    <button type="button" class="btn btn-success float-end text-white text-dark" wire:click="submit">SUBMIT</button>
</div>

</div>

      </div>
    </div>
  </div>
  @livewireScripts
  <script>
    // Redirect after 5 seconds
   
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-livewire-alert::scripts />